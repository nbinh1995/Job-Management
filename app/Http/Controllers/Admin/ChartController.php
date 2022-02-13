<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;

class ChartController extends Controller
{
    public function index()
    {
        $active = 6;
        $chartFrom = Carbon::createFromDate(null, 1, 1)->format('Y-m');
        $chartTo = Carbon::now()->format('Y-m'); 
        return view('admins.chart.index', compact('active','chartFrom' , 'chartTo'));
    }

    public function ajaxTotalPriceMonth(Request $request)
    {
        $chartFrom =  Carbon::createFromFormat('Y-m-d',$request->chartFrom.'-01');
        $chartTo = Carbon::createFromFormat('Y-m-d',$request->chartTo.'-01')->endOfMonth();
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($chartFrom, $interval, $chartTo);
 
        $totalSale = Job::getTotalPriceSaleByYear($chartFrom->toDateString(),$chartTo->toDateString())->get();
        $totalPayment = Job::getTotalPricePaymentByYear($chartFrom->toDateString(),$chartTo->toDateString())->get();
        $totalSaleMonths = [];
        $totalPaymentMonths = [];
        foreach ($period as $dt) {
            $labelChart[] = $dt->format("Y-m");
            $totalSaleMonths[$dt->format("Y-m")]= 0;
            $totalPaymentMonths[$dt->format("Y-m")] =0;
        }
        foreach ($totalSale as $sale) {
            $totalSaleMonths[$sale->month] = (int) $sale->sum_price;
        }
        foreach ($totalPayment as $payment) {
            $totalPaymentMonths[$payment->month] = (int) $payment->sum_price;
        }

        $chartData = [
            'labels' => $labelChart,
            'LineSale' =>array_values($totalSaleMonths),
            'LinePayment' => array_values($totalPaymentMonths)
        ];

        return response()->json(['chartData' => $chartData], 200);
    }
}
