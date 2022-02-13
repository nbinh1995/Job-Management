<?php

namespace App\Models;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{

    protected $table = 'settings';
    protected $fillable = ['key', 'value'];

    public static function set($key, $value = null)
    {
        if ($value != null) {
            $setting = Setting::firstOrNew(array('key' => $key));
            $setting->value = $value;
            if ($setting->save()) {
                return true;
            }
        }
        return false;
    }

    public static function get($key, $default)
    {
        try {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                return $setting->value;
            }
            return $default;
        } catch (\Exception $ex) {
            Log::error('Can not get setting value');
            return $default;
        }
    }
}
