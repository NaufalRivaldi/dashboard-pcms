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
        //  Cabang page
        // ----------------------------------------------------------------
        Route::resource('cabang', 'CabangController');
        Route::prefix('cabang')->group(function(){
            Route::get('json/{param}', 'CabangController@json')->name('cabang.json');
            Route::put('update-status/{type}/{id}', 'CabangController@updateStatus')->name('cabang.update.status');
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        //  Wilayah page
        // ----------------------------------------------------------------
        Route::resource('wilayah', 'WilayahController');
        Route::prefix('wilayah')->group(function(){
            Route::get('json/{param}', 'WilayahController@json')->name('wilayah.json');
            Route::put('update-status/{type}/{id}', 'WilayahController@updateStatus')->name('wilayah.update.status');
        });
        // ----------------------------------------------------------------
        
        // ----------------------------------------------------------------
        //  Sub Wilayah page
        // ----------------------------------------------------------------
        Route::resource('sub-wilayah', 'SubWilayahController');
        Route::prefix('sub-wilayah')->group(function(){
            Route::get('json/{param}', 'SubWilayahController@json')->name('sub-wilayah.json');
            Route::put('update-status/{type}/{id}', 'SubWilayahController@updateStatus')->name('sub-wilayah.update.status');
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        //  Kategori page
        // ----------------------------------------------------------------
        Route::resource('kategori', 'KategoriController');
        Route::prefix('kategori')->group(function(){
            Route::get('json/{param}', 'KategoriController@json')->name('kategori.json');
            Route::put('update-status/{type}/{id}', 'KategoriController@updateStatus')->name('kategori.update.status');
        });
        // ----------------------------------------------------------------
    });
    // --------------------------------------------------------------------
});
// ------------------------------------------------------------------------
