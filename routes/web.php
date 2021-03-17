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

Route::get('/', function(){
    return redirect()->route('login');
});

// ------------------------------------------------------------------------
// Login page
// ------------------------------------------------------------------------
Route::namespace('Auth')->prefix('login')->group(function(){
    Route::get('/', 'LoginController@index')->name('login');
    Route::post('/', 'LoginController@login')->name('login.signin');
    Route::get('/logout', 'LoginController@logout')->name('login.logout');
});
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
// Set namespace for backend controller
// ------------------------------------------------------------------------
Route::namespace('Backend')->middleware('auth')->group(function(){
    // --------------------------------------------------------------------
    // Dashboard page
    // --------------------------------------------------------------------
    Route::prefix('dashboard')->group(function(){
        Route::get('/', 'DashboardController@index')->name('dashboard.index');
    });
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    // Change password page
    // --------------------------------------------------------------------
    Route::namespace('Password')->group(function(){
        Route::get('ubah-password', 'UserController@index')->name('password-user.index');
        Route::post('ubah-password', 'UserController@update')->name('password-user.update');
    });
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    // Master page
    // --------------------------------------------------------------------
    Route::namespace('Master')->prefix('master')->name('master.')->group(function(){
        // ----------------------------------------------------------------
        //  User page
        // ----------------------------------------------------------------
        Route::resource('user', 'UserController');
        Route::prefix('user')->group(function(){
            Route::get('json/{param}', 'UserController@json')->name('user.json');
            Route::put('update-status/{type}/{id}', 'UserController@updateStatus')->name('user.update.status');
            Route::put('reset-password/{id}', 'UserController@resetPassword')->name('user.reset.password');
        });
        // ----------------------------------------------------------------

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

        // ----------------------------------------------------------------
        //  Materi page
        // ----------------------------------------------------------------
        Route::resource('materi', 'MateriController');
        Route::prefix('materi')->group(function(){
            Route::get('json/{param}', 'MateriController@json')->name('materi.json');
            Route::put('update-status/{type}/{id}', 'MateriController@updateStatus')->name('materi.update.status');
            Route::post('grade', 'MateriController@storeGrade')->name('materi.store.grade');
            Route::delete('{id}/grade', 'MateriController@destroyGrade')->name('materi.destroy.grade');
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        //  Grade page
        // ----------------------------------------------------------------
        Route::resource('grade', 'GradeController');
        Route::prefix('grade')->group(function(){
            Route::get('json/{param}', 'GradeController@json')->name('grade.json');
            Route::put('update-status/{type}/{id}', 'GradeController@updateStatus')->name('grade.update.status');
        });
        // ----------------------------------------------------------------
    });
    // --------------------------------------------------------------------
});
// ------------------------------------------------------------------------
