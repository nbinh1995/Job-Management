<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Job\JobRequest;
use App\Http\Requests\Job\UpdateJobRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Job\UpdateCellRequest;
use App\Models\Customer;
use App\Models\JMethod;
use App\Models\Job;
use App\Models\JType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mobile_Detect;
use Yajra\DataTables\Facades\DataTables;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = 1;

        return view('admins.jobs.index', compact('active'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 1;
        $customers = Customer::all();
        $types = JType::all();
        $methods = JMethod::all();

        return view('admins.jobs.create', compact('active', 'customers', 'methods', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobRequest $request)
    {
        $data = $request->except('RealJob', 'Price', 'Paydate', 'Deadline', 'Paid');
        $usd = round(Job::getExRate() * $request->PriceYen, 0);
        if ($usd < 0) {
            return redirect()->back()->with('status', 'error')->with('message', __('Could not get the Exchange Rate.'));
        }
        $data['Price'] = $usd;
        $data['RealJob'] = 1;
        $data['Note'] = $data['Note'] == null ? '' : $data['Note'];
        $data['Paydate'] = Carbon::createFromFormat('Y-m-d', '1111-11-11');
        $data['Deadline'] = $data['StartDate'];
        // dd($data);
        $job = Job::create($data);
        if ($job) {
            return redirect()->back()->with('status', 'success')->with('message', __('Successful add Job to database.'));
        }

        return redirect()->back()->with('status', 'error')->with('message', __('Could not add Job to database. Please try again.'));
    }

    public function ajaxSoftJobDelete(Request $request)
    {
        if ($request->filled('id')) {
            $job = Job::where('ID', $request->id);
            $job->delete();
            return response()->json(['message' => 'success']);
        }
        return response()->json(['message' => 'error']);
    }

    public function ajaxGetJobs(Request $request)
    {
        $jobs = Job::leftJoin('Customer', 'Job.CustomerID', '=', 'Customer.ID')
            ->leftJoin('JMethod', 'Job.MethodID', '=', 'JMethod.ID')
            ->leftJoin('JType', 'Job.TypeID', '=', 'JType.ID')
            ->selectRaw('Job.*, JMethod.Name as MethodName, JType.Name as TypeName, Customer.Name as CustomerName');
        return DataTables::eloquent($jobs)
            ->addColumn('Action', function ($job) {
                return 'action';
            })
            ->removeColumn('RealJob')
            ->toJson();
    }

    public function ajaxUpdateCell(UpdateCellRequest $request, $jobID, $keyJob)
    {
        if ($request->$keyJob) {
            if ($keyJob === 'PriceYen') {
                $usd = round(Job::getExRate() * $request->PriceYen, 0);
                if ($usd < 0) {
                    return response()->json(['message' => 'error'], 422);
                }
                $data['PriceYen'] = $request->PriceYen;
                $data['Price'] = $usd;
                Job::where('ID', $jobID)->update($data);

                return response()->json(['price' => $data['Price'], 'priceYen' => $data['PriceYen'], 'code' => 'price'], 200);
            }

            if ($keyJob === 'Name') {
                $data['Name'] = strtoupper($request->Name);
                Job::where('ID', $jobID)->update($data);

                return response()->json(['attr' => $data['Name'], 'code' => 'normal'], 200);
            }

            if ($keyJob === 'CustomerID') {
                $data[$keyJob] = $request->$keyJob;
                Job::where('ID', $jobID)->update($data);
                $customer = Customer::find($data[$keyJob]);

                return response()->json(['ID' => $customer->ID, 'Name' => $customer->Name, 'code' => 'select'], 200);
            }

            if ($keyJob === 'TypeID') {
                $data[$keyJob] = $request->$keyJob;
                Job::where('ID', $jobID)->update($data);
                $type = JType::find($data[$keyJob]);

                return response()->json(['ID' => $type->ID, 'Name' => $type->Name, 'code' => 'select'], 200);
            }

            if ($keyJob === 'MethodID') {
                $data[$keyJob] = $request->$keyJob;
                Job::where('ID', $jobID)->update($data);
                $method = JMethod::find($data[$keyJob]);

                return response()->json(['ID' => $method->ID, 'Name' => $method->Name, 'code' => 'select'], 200);
            }

            $data[$keyJob] = $request->$keyJob;
            Job::where('ID', $jobID)->update($data);

            return response()->json(['attr' => $data[$keyJob], 'code' => 'normal'], 200);
        } else {
            $job = Job::find($jobID);
            $attr = $job->$keyJob;
            return response()->json(['attr' => $attr, 'code' => 'remove'], 200);
        }
    }

    public function ajaxUpdatePaidCell(UpdateCellRequest $request, $jobID)
    {

        $data['Paid'] = $request->Paid ? 1 : 0;

        Job::where('ID', $jobID)->update($data);

        return response()->json(['massage' => 'success'], 200);
    }

    public function ajaxGetTotalPriceView(Request $request)
    {
        // dd($request->all());
        $now = Carbon::now();
        $totalYen = Job::when($request->filled('Search'), function ($query) use ($request) {
            $query->where('Job.Name', 'LIKE', '%' . $request->get('Search') . '%');
        })->when(!$request->filled('Search'), function ($query) use ($now) {
            $query->whereMonth('StartDate', $now->month)->whereYear('StartDate', $now->year);
        })->sum('PriceYen');
        
        $rate = Job::getExRate();
        $total = round($totalYen * ($rate > 0 ? $rate : 0));
        $thisMonth = true;
        $view = view('partials.common.admin-total_price', compact('totalYen', 'thisMonth', 'total', 'rate'))->render();
        return response()->json(['html' => $view]);
    }

    public function ajaxGetData(Request $request)
    {
        if ($request->ajax()) {
            $types = JType::all();
            $methods = JMethod::all();
            $customers = Customer::all();
            return response()->json(['types' => $types, 'methods' => $methods, 'customers' => $customers], 200);
        }
    }

    public function ajaxGetRateJPY_USD(){
        $rate = Job::getExRate();

        return response()->json(compact('rate'),200);
    }
}
