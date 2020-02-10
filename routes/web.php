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

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/', 'DashboardController@index')->name('voyager.dashboard');
});

// Auth::routes();
Route::get('/login', function(){ return redirect('/admin/login'); })->name('login');
// assignment route getting projects from clients
Route::post('/getprojects', 'AffectationController@getProjects')->name('getProjects');
// assignment route getting prestations from projects
Route::post('/getprestations', 'AffectationController@getPrestations')->name('getPrestations');
// assignment route getting supplier from prestations
Route::post('/getProviders', 'AffectationController@getProviders')->name('getProviders');
// assignment route getting clients if client added (focus)
Route::get('/getclients', 'AffectationController@getClients')->name('getClients');
// new
// assignment route getting clients if client added (focus)
Route::post('/getsites', 'AffectationController@getSites')->name('getSites');


// DASHBOARD Chart data route
Route::post('/chartdata', 'DashboardController@getChartData')->name('chartData');

// journal route
Route::get('/admin/journal', 'JournalController@index')->name('voyager.journal');
// JOURNAL Chart data route
Route::post('/journalchart', 'JournalController@getJournalChart');

// MODAL routes
Route::post('/modaldata', 'ModalsController@storeModalData')->name('storeModal');

// Read View Chart Data
Route::Post('/readchartdata', 'ReadChartController@readData')->name('ReadChart');