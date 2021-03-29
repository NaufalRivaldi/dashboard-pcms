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

    // --------------------------------------------------------------------
    // Import page
    // --------------------------------------------------------------------
    Route::namespace('Import')->prefix('import')->name('import.')->group(function(){
        // ----------------------------------------------------------------
        // LA03 page
        // ----------------------------------------------------------------
        Route::namespace('LA03')->prefix('la03')->group(function(){
            // ------------------------------------------------------------
            // LA03 list page
            // ------------------------------------------------------------
            Route::get('/', 'LA03Controller@index')->name('la03.index');
            Route::get('json/{param}', 'LA03Controller@json')->name('la03.json');
            Route::get('import/', 'LA03Controller@import')->name('la03.import');
            Route::post('import/', 'LA03Controller@importStore')->name('la03.import.store');
            Route::delete('{id}/delete', 'LA03Controller@destroy')->name('la03.destroy');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA03 create page
            // ------------------------------------------------------------
            Route::get('create', 'LA03Controller@create')->name('la03.create');
            Route::get('check-date', 'LA03Controller@checkDataValidation')->name('la03.check-data-validation');
            Route::get('{id}/edit', 'LA03Controller@edit')->name('la03.edit');
            Route::post('/', 'LA03Controller@store')->name('la03.store');
            Route::put('{id}/update', 'LA03Controller@update')->name('la03.update');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA03 detail page
            // ------------------------------------------------------------
            Route::get('{id}', 'LA03DetailController@index')->name('la03.show');
            Route::get('{id}/pdf', 'LA03DetailController@exportPdf')->name('la03.show.pdf');
            Route::get('{id}/json/{param}', 'LA03DetailController@json')->name('la03.show.json');
            Route::delete('{id}/delete-detail', 'LA03DetailController@destroy')->name('la03.show.destroy');
            Route::put('{id}/update-detail', 'LA03DetailController@update')->name('la03.show.update');
            Route::put('{id}/accept-detail', 'LA03DetailController@accept')->name('la03.show.accept');
            // ------------------------------------------------------------
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        // LA06 page
        // ----------------------------------------------------------------
        Route::namespace('LA06')->group(function(){
            // ------------------------------------------------------------
            // LA06 list page
            // ------------------------------------------------------------
            Route::get('la06/', 'LA06Controller@index')->name('la06.index');
            Route::get('la06/json/{param}', 'LA06Controller@json')->name('la06.json');
            Route::get('la06/import/', 'LA06Controller@import')->name('la06.import');
            Route::post('la06/import/', 'LA06Controller@importStore')->name('la06.import.store');
            Route::delete('la06/{id}/delete', 'LA06Controller@destroy')->name('la06.destroy');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA06 create / edit page
            // ------------------------------------------------------------
            Route::get('la06/create', 'LA06CreateController@index')->name('la06.create');
            Route::get('la06/check-date', 'LA06CreateController@checkDataValidation')->name('la06.check-data-validation');
            Route::get('la06/{id}/edit', 'LA06CreateController@edit')->name('la06.edit');
            Route::post('la06/', 'LA06CreateController@store')->name('la06.store');
            Route::put('la06/{id}/update', 'LA06CreateController@update')->name('la06.update');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA06 detail page
            // ------------------------------------------------------------
            Route::get('la06/{id}', 'LA06DetailController@index')->name('la06.show');
            Route::get('la06/{id}/pdf', 'LA06DetailController@exportPdf')->name('la06.show.pdf');
            Route::get('la06/{id}/json/{param}', 'LA06DetailController@json')->name('la06.show.json');
            Route::delete('la06/{id}/delete-detail', 'LA06DetailController@destroy')->name('la06.show.destroy');
            Route::put('la06/{id}/update-detail', 'LA06DetailController@update')->name('la06.show.update');
            Route::put('la06/{id}/accept-detail', 'LA06DetailController@accept')->name('la06.show.accept');
            // ------------------------------------------------------------
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        // LA06 page
        // ----------------------------------------------------------------
        Route::namespace('LA07')->group(function(){
            // ------------------------------------------------------------
            // LA07 list page
            // ------------------------------------------------------------
            Route::get('la07/', 'LA07Controller@index')->name('la07.index');
            Route::get('la07/json/{param}', 'LA07Controller@json')->name('la07.json');
            Route::get('la07/import/', 'LA07Controller@import')->name('la07.import');
            Route::post('la07/import/', 'LA07Controller@importStore')->name('la07.import.store');
            Route::delete('la07/{id}/delete', 'LA07Controller@destroy')->name('la07.destroy');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA07 create / edit page
            // ------------------------------------------------------------
            Route::get('la07/create', 'LA07CreateController@index')->name('la07.create');
            Route::get('la07/check-date', 'LA07CreateController@checkDataValidation')->name('la07.check-data-validation');
            Route::get('la07/{id}/edit', 'LA07CreateController@edit')->name('la07.edit');
            Route::post('la07/', 'LA07CreateController@store')->name('la07.store');
            Route::put('la07/{id}/update', 'LA07CreateController@update')->name('la07.update');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA07 detail page
            // ------------------------------------------------------------
            Route::get('la07/{id}', 'LA07DetailController@index')->name('la07.show');
            Route::get('la07/{id}/pdf', 'LA07DetailController@exportPdf')->name('la07.show.pdf');
            Route::get('la07/{id}/json/{param}', 'LA07DetailController@json')->name('la07.show.json');
            Route::delete('la07/{id}/delete-detail', 'LA07DetailController@destroy')->name('la07.show.destroy');
            Route::put('la07/{id}/update-detail', 'LA07DetailController@update')->name('la07.show.update');
            Route::put('la07/{id}/accept-detail', 'LA07DetailController@accept')->name('la07.show.accept');
            // ------------------------------------------------------------
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        // LA06 page
        // ----------------------------------------------------------------
        Route::namespace('LA09')->group(function(){
            // ------------------------------------------------------------
            // LA09 list page
            // ------------------------------------------------------------
            Route::get('la09/', 'LA09Controller@index')->name('la09.index');
            Route::get('la09/json/{param}', 'LA09Controller@json')->name('la09.json');
            Route::get('la09/import/', 'LA09Controller@import')->name('la09.import');
            Route::post('la09/import/', 'LA09Controller@importStore')->name('la09.import.store');
            Route::delete('la09/{id}/delete', 'LA09Controller@destroy')->name('la09.destroy');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA09 create / edit page
            // ------------------------------------------------------------
            Route::get('la09/create', 'LA09CreateController@index')->name('la09.create');
            Route::get('la09/check-date', 'LA09CreateController@checkDataValidation')->name('la09.check-data-validation');
            Route::get('la09/{id}/edit', 'LA09CreateController@edit')->name('la09.edit');
            Route::post('la09/', 'LA09CreateController@store')->name('la09.store');
            Route::put('la09/{id}/update', 'LA09CreateController@update')->name('la09.update');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA09 detail page
            // ------------------------------------------------------------
            Route::get('la09/{id}', 'LA09DetailController@index')->name('la09.show');
            Route::get('la09/{id}/pdf', 'LA09DetailController@exportPdf')->name('la09.show.pdf');
            Route::put('la09/{id}/accept-detail', 'LA09DetailController@accept')->name('la09.show.accept');
            // ------------------------------------------------------------
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        // LA06 page
        // ----------------------------------------------------------------
        Route::namespace('LA11')->group(function(){
            // ------------------------------------------------------------
            // LA11 list page
            // ------------------------------------------------------------
            Route::get('la11/', 'LA11Controller@index')->name('la11.index');
            Route::get('la11/json/{param}', 'LA11Controller@json')->name('la11.json');
            Route::get('la11/import/', 'LA11Controller@import')->name('la11.import');
            Route::post('la11/import/', 'LA11Controller@importStore')->name('la11.import.store');
            Route::delete('la11/{id}/delete', 'LA11Controller@destroy')->name('la11.destroy');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA11 create / edit page
            // ------------------------------------------------------------
            Route::get('la11/create', 'LA11CreateController@index')->name('la11.create');
            Route::get('la11/check-date', 'LA11CreateController@checkDataValidation')->name('la11.check-data-validation');
            Route::get('la11/{id}/edit', 'LA11CreateController@edit')->name('la11.edit');
            Route::post('la11/', 'LA11CreateController@store')->name('la11.store');
            Route::put('la11/{id}/update', 'LA11CreateController@update')->name('la11.update');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA11 detail page
            // ------------------------------------------------------------
            Route::get('la11/{id}', 'LA11DetailController@index')->name('la11.show');
            Route::get('la11/{id}/pdf', 'LA11DetailController@exportPdf')->name('la11.show.pdf');
            Route::put('la11/{id}/accept-detail', 'LA11DetailController@accept')->name('la11.show.accept');
            // ------------------------------------------------------------
        });
        // ----------------------------------------------------------------

        // ----------------------------------------------------------------
        // LA06 page
        // ----------------------------------------------------------------
        Route::namespace('LA12')->group(function(){
            // ------------------------------------------------------------
            // LA12 list page
            // ------------------------------------------------------------
            Route::get('la12/', 'LA12Controller@index')->name('la12.index');
            Route::get('la12/json/{param}', 'LA12Controller@json')->name('la12.json');
            Route::get('la12/import/', 'LA12Controller@import')->name('la12.import');
            Route::post('la12/import/', 'LA12Controller@importStore')->name('la12.import.store');
            Route::delete('la12/{id}/delete', 'LA12Controller@destroy')->name('la12.destroy');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA12 create / edit page
            // ------------------------------------------------------------
            Route::get('la12/create', 'LA12CreateController@index')->name('la12.create');
            Route::get('la12/check-date', 'LA12CreateController@checkDataValidation')->name('la12.check-data-validation');
            Route::get('la12/{id}/edit', 'LA12CreateController@edit')->name('la12.edit');
            Route::post('la12/', 'LA12CreateController@store')->name('la12.store');
            Route::put('la12/{id}/update', 'LA12CreateController@update')->name('la12.update');
            // ------------------------------------------------------------

            // ------------------------------------------------------------
            // LA12 detail page
            // ------------------------------------------------------------
            Route::get('la12/{id}', 'LA12DetailController@index')->name('la12.show');
            Route::get('la12/{id}/pdf', 'LA12DetailController@exportPdf')->name('la12.show.pdf');
            Route::put('la12/{id}/accept-detail', 'LA12DetailController@accept')->name('la12.show.accept');
            // ------------------------------------------------------------
        });
        // ----------------------------------------------------------------
    });
    // --------------------------------------------------------------------
});
// ------------------------------------------------------------------------
