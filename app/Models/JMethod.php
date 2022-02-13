<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JMethod extends Model
{
    const PRIMARY_KEY_TABLE = "ID";

    use SoftDeletes;
    protected $table = 'JMethod';
    protected $guarded = [];

    public function jobs()
    {

        return $this->hasMany(Job::class, Job::FOREIGN_KEY_JMETHOD, self::PRIMARY_KEY_TABLE)->orderBy('created_at', 'DESC');
    }
}
