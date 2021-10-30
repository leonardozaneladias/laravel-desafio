<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'tenant', 'bindings'], 'namespace' => 'Admin'], function () {

    //Home
    Route::get('home', 'DashboardController@index')->name('home');
    Route::get('home/reload', 'DashboardController@reload')->name('home.reload');

    //Websites
    Route::get('website', 'WebsitesController@index')->name('website');
    Route::get('websites', 'WebsitesController@list')->name('websites');
    Route::post('website', 'WebsitesController@store')->name('website.store');
    Route::get('website/{website}', 'WebsitesController@show')->name('website.show');
    Route::delete('website/{website}', 'WebsitesController@destroy')->name('website.destroy');
    Route::get('websites/process', 'WebsitesController@processStatus')->name('website.process');
    
    //Websites Status
    Route::get('website/{website}/status', 'WebsiteStatusController@index')->name('website.status.index');

});
