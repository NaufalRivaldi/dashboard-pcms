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

// ------------------------------------------------------------------------
// Login page
// ------------------------------------------------------------------------
Route::namespace('Auth')->prefix('login')->group(function(){
    Route::get('/', 'LoginController@index')->name('login');
});
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
// Set namespace for backend controller
// ------------------------------------------------------------------------
Route::namespace('Backend')->group(function(){
    // --------------------------------------------------------------------
    // Dashboard page
    // --------------------------------------------------------------------
    Route::prefix('dashboard')->group(function(){
        Route::get('/', 'DashboardController@index')->name('dashboard.index');
    });
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    // Master page
    // --------------------------------------------------------------------
    Route::namespace('Master')->prefix('master')->name('master.')->group(function(){
        // ----------------------------------------------------------------
        //  Sub Wilayah page
        // ----------------------------------------------------------------
        Route::resource('sub-wilayah', 'SubWilayahController');
        Route::prefix('sub-wilayah')->group(function(){
            Route::get('json/{param}', 'SubWilayahController@json')->name('sub-wilayah.json');
            Route::put('update-status/{type}/{id}', 'SubWilayahController@updateStatus')->name('sub-wilayah.update.status');
        });
        // ----------------------------------------------------------------
    });
    // --------------------------------------------------------------------
});
// ------------------------------------------------------------------------
