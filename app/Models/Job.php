<?php

namespace App\Models;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Job extends Model
{
    const FOREIGN_KEY_CUSTOMER = 'CustomerID';
    const FOREIGN_KEY_JMETHOD = 'MethodID';
    const FOREIGN_KEY_JTYPE = 'TypeID';
    // const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    const GET_DATE_FORMAT = 'Y-m-d';
    const SET_DATE_FORMAT = 'd/m/Y';

    use SoftDeletes;

    protected $table = 'Job';

    public function customer()
    {
        return $this->belongsTo(Customer::class, self::FOREIGN_KEY_CUSTOMER, Customer::PRIMARY_KEY_TABLE)->withTrashed();
    }

    public function jmethod()
    {
        return $this->belongsTo(JMethod::class, self::FOREIGN_KEY_JMETHOD, JMethod::PRIMARY_KEY_TABLE)->withTrashed();
    }

    public function jtype()
    {
        return $this->belongsTo(JType::class, self::FOREIGN_KEY_JTYPE, JType::PRIMARY_KEY_TABLE)->withTrashed();
    }

    protected $fillable = [
        'Name',
        'CustomerID',
        'TypeID',
        'StartDate',
        'RealJob',
        'Deadline',
        'Price',
        'PriceYen',
        'MethodID',
        'Paydate',
        'FinishDate',
        'Note',
    ];

    public static function getExRate()
    {
        try {
            if(session()->has('rate') && (time() - session('rate')['time'] >= 86400)){
                session()->forget('rate');
            }
            if(!session()->has('rate')){
                $exRateUrl = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
                $client = new Client();
                $req = $client->get($exRateUrl);
                $data = $req->getBody()->getContents();
                $regexUSD = "/Cube currency='USD' rate='(\d*(\.\d+)?)'/";
                $regexJPY = "/Cube currency='JPY' rate='(\d*(\.\d+)?)'/";
                preg_match($regexUSD, $data, $rateUSD);
                preg_match($regexJPY,$data,$rateJPY);
                $rateJPY_USD = round(((float) $rateUSD[1]/ (float)$rateJPY[1]),4);
                session()->put('rate',['value'=>$rateJPY_USD,'time'=>time()]);
                return $rateJPY_USD;
            }
            return session('rate')['value'];
        } catch (\Exception $exception) {
            return -1;
        }
    }


    public static function getTotalPriceSaleByYear($from,$to)
    {
        return DB::table('Job')->groupBy('month')
            ->orderBy('month')
            ->whereDate('Job.StartDate','>=', $from)
            ->whereDate('Job.StartDate','<=', $to)
            ->where('RealJob', 1)
            ->selectRaw('SUM(Job.Price) as sum_price,DATE_FORMAT(Job.StartDate,"%Y-%m")as month');
    }

    public static function getTotalPricePaymentByYear($from,$to)
    {
        return DB::table('Job')->groupBy('month')
            ->orderBy('month')
            ->whereDate('Job.Paydate','>=',$from)
            ->whereDate('Job.Paydate','<=', $to)
            ->where('RealJob', 1)
            ->selectRaw('SUM(Job.Price) as sum_price,DATE_FORMAT(Job.Paydate,"%Y-%m") as month');
    }

    public static function getPriceDaysByMonth($fromDate, $toDate)
    {
        return DB::table('Job')->groupBy('date')
            ->orderBy('date')
            ->whereBetween('Job.Paydate', [$fromDate, $toDate])
            ->where('Paid', 1)
            ->selectRaw('SUM(Job.Price) as sum_price,DATE(Job.Paydate) as date');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['Name'] = strtoupper($value);
    }
}
