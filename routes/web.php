<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/index', 'Site\SiteController@index')->name('home');
Route::get('/', 'Site\SiteController@index')->name('home');

Auth::routes(['register' => false]);

Route::prefix('admin')->middleware('auth')->namespace('Admin')->group(function () {

    // Home
    Route::get('/index', 'HomeController@index')->name('admin');
    Route::get('/', 'HomeController@index')->name('admin');

    // Admin access routes
    Route::middleware('CheckAdminPermission')->group(function () {

        // Poll
        Route::prefix('poll')->group(function () {
            Route::get('/index', 'PollController@index')->name('poll');
            Route::get('/', 'PollController@index')->name('poll');
            Route::name('poll.')->group(function () {
                Route::get('/create', 'PollController@createForm')->name('createForm');
                Route::get('/edit/{id}', 'PollController@editForm')->name('editForm');
                Route::post('/createorupdate', 'PollController@createorupdate')->name('createorupdate');
                Route::post('/poll-options', 'PollController@getPollOptions')->name('options');
                Route::delete('/delete', 'PollController@delete')->name('delete');
            });

            // Category
            Route::prefix('category')->group(function () {
                Route::get('/index', 'PollCategoryController@category')->name('category');
                Route::get('/', 'PollCategoryController@category')->name('category');
                Route::name('category.')->group(function () {
                    Route::post('/createorupdate', 'PollCategoryController@categoryCreateorupdate')->name('createorupdate');
                    Route::post('/slug', 'PollCategoryController@getSlugUrl')->name('slug');
                    Route::delete('/delete', 'PollCategoryController@categoryDelete')->name('delete');
                });
            });
        });

        // User
        // Route::prefix('user')->group(function () {
        //     Route::get('/index', 'UserController@index')->name('user');
        //     Route::get('/', 'UserController@index')->name('user');
        //     Route::name('user.')->group(function () {
        //         Route::post('/createorupdate', 'UserController@createorupdate')->name('createorupdate');
        //         Route::post('/updatestatus', 'UserController@userStatus')->name('userStatus');
        //         Route::delete('/delete', 'UserController@delete')->name('delete');
        //     });
        // });

        //Codeblock
        Route::prefix('codeblock')->group(function () {
            Route::get('/index', 'CodeblockController@index')->name('codeblock');
            Route::get('/', 'CodeblockController@index')->name('codeblock');
            Route::name('codeblock.')->group(function () {
                Route::post('/createorupdate', 'CodeblockController@createorupdate')->name('createorupdate');
            });
        });

        //Setting
        // Route::prefix('setting')->group(function () {
        //     Route::get('/index', 'SettingController@index')->name('setting');
        //     Route::get('/', 'SettingController@index')->name('setting');
        //     Route::name('setting.')->group(function () {
        //         Route::post('/createorupdate', 'SettingController@createorupdate')->name('createorupdate');
        //     });
        // });
    });

    // Common access routes
    // Profile Update
    Route::get('/profile', 'UserProfileController@index')->name('userProfile');
    Route::post('/profile/update', 'UserProfileController@update')->name('userProfile.update');

    // Chnage Password
    Route::get('/profile/password', 'UserProfileController@password')->name('userProfile.password');
    Route::post('/profile/password/update', 'UserProfileController@passwordUpdate')->name('userProfile.passwordUpdate');
});

Route::prefix('poll')->namespace('Admin')->name('poll.')->group(function () {
    Route::get('/{slug}', 'PollController@view')->name('view');
    Route::middleware('CheckAdminPermission')->group(function () {
        Route::get('/embed/{slug}', 'PollController@embedView')->name('embedView');
    });
    Route::post('/voting', 'PollController@Voting')->name('voting');
});

Route::name('site.')->namespace('Site')->group(function () {
    Route::get('/category/{slug}', 'SiteController@getCategoryView')->name('getCategoryView');
    Route::get('/about', 'SiteController@about')->name('about');
    Route::get('/contact', 'SiteController@contact')->name('contact');
    Route::get('/privacy-policy', 'SiteController@privacyPolicy')->name('privacyPolicy');
});

// Clear all cache
Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('clear-compiled');
    dd("Cache is cleared");
});
