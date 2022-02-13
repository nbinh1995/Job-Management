<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Report\ReportRequest;
use App\Models\Customer;
use App\Models\JMethod;
use App\Models\Job;
use App\Models\JType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $active = 5;
        $now = Carbon::now();
        if ($request->filled('fromDate') && preg_match('/([0-9]{4}-([0-9]{2})-([0-9]{2}))/', $request->fromDate)) {
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->fromDate)->format('Y-m-d');
        } else {
            $fromDate = Carbon::createFromDate($now->year, $now->month, 1)->format('Y-m-d');
        }
        if ($request->filled('toDate') && preg_match('/([0-9]{4}-([0-9]{2})-([0-9]{2}))/', $request->toDate)) {
            $toDate = Carbon::createFromFormat('Y-m-d', $request->toDate)->format('Y-m-d');
        } else {
            $toDate = Carbon::createFromDate($now->year, $now->month + 1, 1)->addDay(-1)->format('Y-m-d');
        }
        if ($request->filled('reportType')) {
            $reportType = $request->reportType;
        } else {
            $reportType = 0;
        }
        $customers = Customer::all();
        $types = JType::all();
        $methods = JMethod::all();
        //$real = $request->filled('RealJob') ? $request->RealJob : 1;
        $paid = $request->filled('Paid') ? $request->Paid : 0;
        $search = $request->filled('Search') ? $request->Search : '';

        $eloquent = $this->filterJobs($request);
        $totalYen = $eloquent->sum('PriceYen');

        return view('admins.report.index', compact('active', 'fromDate', 'toDate', 'reportType', 'totalYen', 'customers', 'types', 'methods', 'paid', 'search'));
    }

    public function ajaxGetReports(Request $request)
    {
        $eloquent = $this->filterJobs($request);
        $eloquent->selectRaw('Job.*, JMethod.Name as MethodName, JType.Name as TypeName, Customer.Name as CustomerName');
        return DataTables::eloquent($eloquent)
            ->removeColumn('RealJob')
            ->addColumn('Action', function ($job) {
                return 'action';
            })
            ->toJson();
    }

    public function ajaxGetChartData(Request $request)
    {
        $eloquent = $this->filterJobs($request);
        $sumEloquent = clone $eloquent;
        $total = $sumEloquent->when($request->filled('Search'), function ($query) use ($request) {
            $query->where('Job.Name', 'LIKE', '%' . $request->get('Search') . '%');
        })->sum('PriceYen');
        $data = $eloquent
            ->when($request->filled('Search'), function ($query) use ($request) {
                $query->where('Job.Name', 'LIKE', '%' . $request->get('Search') . '%');
            })
            ->crossJoin(DB::raw('(SELECT ' . $total . ' AS Total) t'))
            ->selectRaw('CustomerID, Customer.Name as CustomerName, SUM(PriceYen) as TotalPrice, round(SUM(PriceYen)/Total * 100,2) as Percent')
            ->groupBy(DB::raw('CustomerID, CustomerName, Total'))->get();
        return response()->json($data);
    }

    private function filterJobs(Request $request)
    {
        $now = Carbon::now();
        if ($request->filled('fromDate') && preg_match('/([0-9]{4}-([0-9]{2})-([0-9]{2}))/', $request->fromDate)) {
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->fromDate)->format('Y-m-d');
        } else {
            $fromDate = Carbon::createFromDate($now->year, $now->month, 1)->format('Y-m-d');
        }
        if ($request->filled('toDate') && preg_match('/([0-9]{4}-([0-9]{2})-([0-9]{2}))/', $request->toDate)) {
            $toDate = Carbon::createFromFormat('Y-m-d', $request->toDate)->format('Y-m-d');
        } else {
            $toDate = Carbon::createFromDate($now->year, $now->month + 1, 1)->addDay(-1)->format('Y-m-d');
        }

        $jobs = Job::leftJoin('Customer', 'CustomerID', '=', 'Customer.ID')
            ->leftJoin('JMethod', 'MethodID', '=', 'JMethod.ID')
            ->leftJoin('JType', 'TypeID', '=', 'JType.ID')
            ->where(function ($query) use ($request) {
                $query->when(($request->filled('CustomerID') && $request->CustomerID != 0), function ($query) use ($request) {
                    $query->where('Job.CustomerID', $request->CustomerID);
                })
                    ->when(($request->filled('TypeID') && $request->TypeID != 0), function ($query) use ($request) {
                        $query->where('Job.TypeID', $request->TypeID);
                    })
                    ->when(($request->filled('MethodID') && $request->MethodID != 0), function ($query) use ($request) {
                        $query->where('Job.MethodID', $request->MethodID);
                    });
            })
            ->where(function ($query) use ($request, $fromDate, $toDate) {
                $query->when($request->filled('reportType'), function ($query) use ($fromDate, $toDate, $request) {
                    switch ($request->reportType) {
                        case 1:
                            $sql = 'StartDate BETWEEN ? AND ?';
                            $query->whereRaw($sql, [$fromDate, $toDate]);
                            break;
                        default:
                            $sql = 'Paydate BETWEEN ? AND ?';
                            $query->whereRaw($sql, [$fromDate, $toDate]);
                            break;
                    }
                })
                    ->when(!$request->filled('reportType'), function ($query) use ($fromDate, $toDate, $request) {
                        $query->where(function ($query) use ($fromDate, $toDate) {
                            $query->whereRaw('StartDate BETWEEN ? AND ?', [$fromDate, $toDate]);
                        });
                    });
            })
            ->where(function ($query) use ($request) {
                switch ($request->Paid) {
                    case 1:
                        $query->where('Paid', 1);
                        break;
                    case 2:
                        $query->where('Paid', 0);
                        break;
                    default:
                        break;
                }
            });
        return $jobs;
    }

    public function ajaxGetTotalPriceView(Request $request)
    {
        $totalYen = $this->filterJobs($request)
            ->when($request->filled('Search'), function ($query) use ($request) {
                $query->where('Job.Name', 'LIKE', '%' . $request->get('Search') . '%');
            })
            ->sum('PriceYen');
        $rate = Job::getExRate();
        $total = round($totalYen * ($rate > 0 ? $rate : 0));

        $view = view('partials.common.admin-total_price', compact('totalYen', 'total', 'rate'))->render();
        return response()->json(['html' => $view]);
    }
}
