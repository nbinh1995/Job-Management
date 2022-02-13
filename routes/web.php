<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'JobController@index')->name('home');
    Route::get('/ajax/get-rate-jpy-usd','JobController@ajaxGetRateJPY_USD')->name('ajaxGetRateJPY_USD');
    Route::group(['prefix' => 'backup'], function () {
        Route::get('/', 'BackupController@index')->name('backup.index');
        Route::get('/list', 'BackupController@list')->name('backup.list');
        Route::get('/manual', 'BackupController@backupManual')->name('backup.manual');
        Route::post('/download', 'BackupController@download')->name('backup.download');
        Route::delete('/destroy', 'BackupController@destroy')->name('backup.destroy');
    });

    Route::group(['prefix' => 'jobs'], function () {
        Route::get('/create', 'JobController@create')->name('jobs.create');
        Route::post('/', 'JobController@store')->name('jobs.store');
        Route::delete('/{jobID}', 'JobController@destroy')->name('jobs.destroy');
    });

    Route::group(['prefix' => 'ajax'], function () {

        Route::post('ajaxGetTotalPriceMonth', 'ChartController@ajaxTotalPriceMonth')->name('ajaxTotalPriceMonth');
        Route::post('ajaxGetJobs', 'JobController@ajaxGetJobs')->name('ajaxGetJobs');
        Route::post('ajaxSoftJobDelete', 'JobController@ajaxSoftJobDelete')->name('ajaxSoftJobDelete');
        Route::put('ajaxUpdateCell/{jobID}/{keyJob}', 'JobController@ajaxUpdateCell')->name('ajaxUpdateCell');
        Route::put('ajaxUpdatePaidCell/{jobID}', 'JobController@ajaxUpdatePaidCell')->name('ajaxUpdatePaidCell');
        Route::post('ajaxGetReports', 'ReportController@ajaxGetReports')->name('ajaxGetReports');
        Route::post('ajaxGetChartData', 'ReportController@ajaxGetChartData')->name('ajaxGetChartData');
        Route::post('ajaxGetUsers', 'UserController@ajaxGetUsers')->name('ajaxGetUsers');
        Route::post('ajaxGetTotalPriceView', 'ReportController@ajaxGetTotalPriceView')->name('ajaxGetTotalPriceView');
        Route::post('ajaxGetTotalThisMonthView', 'JobController@ajaxGetTotalPriceView')->name('ajaxGetTotalThisMonthView');
        Route::post('ajaxGetMethods', 'JMethodController@ajaxGetMethods')->name('ajaxGetMethods');
        Route::post('ajaxGetTypes', 'JTypeController@ajaxGetTypes')->name('ajaxGetTypes');
        Route::post('ajaxGetCustomers', 'CustomerController@ajaxGetCustomers')->name('ajaxGetCustomers');
        Route::post('ajaxGetData', 'JobController@ajaxGetData')->name('ajaxGetData');
        Route::put('ajaxUpdateUnpaid', 'SettingController@ajaxupdateUnpaid')->name('ajaxUpdateUnpaid');
        Route::put('ajaxUpdateKeepDays', 'SettingController@ajaxUpdateKeepDays')->name('ajaxUpdateKeepDays');
        Route::post('ajaxGetUnPaidCount', 'CustomerController@ajaxGetUnPaidCount')->name('ajaxGetUnPaidCount');
    });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', 'CustomerController@index')->name('customers.index');
        Route::get('/list', 'CustomerController@list')->name('customers.list');
        Route::get('/create', 'CustomerController@create')->name('customers.create');
        Route::post('/', 'CustomerController@store')->name('customers.store');
        Route::put('/{customerID}/{nameAttr}', 'CustomerController@update')->name('customers.update');
        Route::delete('/{customerID}', 'CustomerController@destroy')->name('customers.destroy');
    });

    Route::group(['prefix' => 'jmethods'], function () {
        Route::get('/', 'JMethodController@index')->name('jmethods.index');
        Route::get('/list', 'JMethodController@list')->name('jmethods.list');
        Route::get('/create', 'JMethodController@create')->name('jmethods.create');
        Route::post('/', 'JMethodController@store')->name('jmethods.store');
        Route::get('/{jmethodID}/edit', 'JMethodController@edit')->name('jmethods.edit');
        Route::put('/{jmethodID}', 'JMethodController@update')->name('jmethods.update');
        Route::delete('/{jmethodID}', 'JMethodController@destroy')->name('jmethods.destroy');
    });

    Route::group(['prefix' => 'jtypes'], function () {
        Route::get('/', 'JTypeController@index')->name('jtypes.index');
        Route::get('/list', 'JTypeController@list')->name('jtypes.list');
        Route::get('/create', 'JTypeController@create')->name('jtypes.create');
        Route::post('/', 'JTypeController@store')->name('jtypes.store');
        Route::get('/{jtypeID}/edit', 'JTypeController@edit')->name('jtypes.edit');
        Route::put('/{jtypeID}', 'JTypeController@update')->name('jtypes.update');
        Route::delete('/{jtypeID}', 'JTypeController@destroy')->name('jtypes.destroy');
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', 'ReportController@index')->name('reports.index');
        Route::get('/chart', 'ChartController@index')->name('reports.chart');
    });

    /*Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingController@index')->name('settings.index');
        Route::put('/', 'SettingController@update')->name('settings.update');
    });*/

});

//add middleware admin and change to this
Route::group(['namespace' => 'Admin', 'prefix' => 'users'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/password', 'UserController@password')->name('users.password');
        Route::post('/password', 'UserController@changePassword')->name('users.changePassword');
    });
    Route::group(['middleware' => 'admin'], function () {
        Route::get('/', 'UserController@index')->name('users.index');
        Route::get('/create', 'UserController@create')->name('users.create');
        Route::delete('/{id}', 'UserController@destroy')->name('users.destroy');
        Route::get('/active/{id}', 'UserController@active')->name('users.active');
        Route::post('/', 'UserController@store')->name('users.store');
    });
});

Route::group(['middleware' => 'guest', 'namespace' => 'Auth'], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
});

Route::group(['middleware' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('/logout', 'LoginController@logout')->name('logout');
});