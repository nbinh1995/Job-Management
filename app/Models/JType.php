<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JType extends Model
{
    const PRIMARY_KEY_TABLE = "ID";

    use SoftDeletes;
    protected $table = 'JType';
    protected $guarded = [];

    public function jobs()
    {
        return $this->hasMany(Job::class, Job::FOREIGN_KEY_JTYPE, self::PRIMARY_KEY_TABLE)->orderBy('created_at', 'DESC');
    }
}
