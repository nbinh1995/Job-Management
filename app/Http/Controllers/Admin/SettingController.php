<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Setting\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $unpaid_amount = Setting::get('unpaid_amount', config('job.unpaid-amount'));
        return view('admins.setting.index', ['active' => 9, 'unpaid_amount' => $unpaid_amount]);
    }

    public function ajaxUpdateUnpaid(UpdateRequest $request)
    {
        if($request->ajax()) {
            $unpaid_amount = $request->get('unpaid-amount');
            if (Setting::set('unpaid_amount', $unpaid_amount)) {
                return response()->json(['status' => 'success', 'message' => __('Settings saved.')], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => __('Can not save to database. Please try again.')]);
            }
        }
        return response()->json(['status' => 'error', 'message' => __('Method not allowed.')]);
    }

    public function ajaxUpdateKeepDays(UpdateRequest $request)
    {
        if($request->ajax()) {
            $keep_days = $request->get('keep-days');
            if (Setting::set('keep_days', $keep_days)) {
                return response()->json(['status' => 'success', 'message' => __('Settings saved.')], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => __('Can not save to database. Please try again.')]);
            }
        }
        return response()->json(['status' => 'error', 'message' => __('Method not allowed.')]);
    }
}
