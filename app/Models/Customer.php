<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    const PRIMARY_KEY_TABLE = "ID";
    const JOB_TABLE = 'Job';

    use SoftDeletes;

    protected $table = 'Customer';
    protected $guarded = [];

    public function jobs()
    {
        return $this->hasMany(Job::class, Job::FOREIGN_KEY_CUSTOMER, self::PRIMARY_KEY_TABLE)->orderBy('created_at', 'DESC');
    }

    public function scopeWithUnPaid($query)
    {
        return $query->leftJoin(\DB::raw('(select Job.CustomerID , SUM(Job.Price) AS UnPaid from Job where Job.Paid = 0 group by CustomerID ) up'), function ($q) {
            $q->on('Customer.ID', '=', 'up.CustomerID');
        });
    }

    public function scopeUnpaid($query, $unpaid_amount)
    {
        return $query->withUnPaid()->whereRaw("up.UnPaid >= $unpaid_amount");
    }
}
