<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest as CustomerCustomerRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Show index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $unpaid_amount = Setting::get('unpaid_amount', config('job.unpaid-amount'));
        return view('admins.customer.index', ['active' => 2, 'unpaid_amount' => $unpaid_amount]);
    }

    /**
     * display list
     *
     * @return void
     */
    public function list(Request $request)
    {

        $customers = Customer::when($request->has('advanced_sort'), function ($query) use ($request) {
            switch ($request->advanced_sort) {
                case 1:
                    $query->latest();
                    break;
                case 2:
                    $query->oldest();
                    break;
                default:
                    $query->selectRaw('*, (select max(created_at) from Job where Customer.ID = Job.CustomerID) as recent')->orderBy('recent', 'desc');
                    break;
            }
        })
            ->when($request->unpaid == '0', function ($query) {
                $query->withUnPaid();
            })
            ->when($request->unpaid == '1', function ($query) {
                $query->unpaid(Setting::get('unpaid_amount', config('job.unpaid-amount')));
            });

        $datatables = DataTables::eloquent($customers)
            ->addColumn('Action', 'action')
            ->rawColumns(['Note'])
            ->toJson();
        return $datatables;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $html = view('partials.form.form-create_customer')->render();

        return response()->json(['html' => $html], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerCustomerRequest $request)
    {
        $data = [];
        if ($request->Name) {
            $data['Name'] = Str::upper($request->Name);
        }
        Customer::create($data);

        return response()->json(['code' => 201], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $customerID
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customerID)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $customerID
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, $customerID, $nameAttr)
    {
        $data = [];
        if ($request->$nameAttr || $nameAttr === 'Note') {
            if ($nameAttr === 'Name') {
                $data['Name'] = Str::upper($request->Name);
                $customer = Customer::where('ID', $customerID);
                $customer->update($data);
                return response()->json(['attr' => $data['Name']], 200);
            }


            $data[$nameAttr] = $request->$nameAttr;

            Customer::where('ID', $customerID)->update($data);

            return response()->json(['attr' => $data[$nameAttr]], 200);
        } else {
            $customer = Customer::find($customerID);
            $attr = $customer->$nameAttr;
            return response()->json(['attr' => $attr], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $customerID
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerID)
    {
        $customer = Customer::where('ID', $customerID);
        $customer->delete();

        return response()->json(['code' => 204], 200);
    }

    public function ajaxGetCustomers(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(Customer::all());
        }
    }

    public function ajaxGetUnPaidCount(Request $request)
    {
        if ($request->ajax()) {
            $unpaidCount = Customer::unpaid(Setting::get('unpaid_amount', config('job.unpaid-amount')))->count();
            return response()->json(['unpaid_count' => $unpaidCount], 200);
        }
    }
}
