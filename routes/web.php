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

Route::get('/plan-expired-notification', 'Admin\FcmpushnotificationsController@planexpiredNotification')->name('planexpiredNotification');

/* ----------------------- Admin Route(s) START -------------------------------- */
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {

    // Admin Authentication Route(s)
    Route::namespace('Auth')->group(function() {

        //Login Route(s)
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login');
        Route::post('/logout', 'LoginController@logout')->name('logout');

        //Forgot Password Route(s)
        Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        //Reset Password Route(s)
        Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');

    });

    //Admin route(s) list
    Route::group(['middleware' => 'auth:admin'], function() {
        //Clear application cache
        Route::get('/cache-clear', function () {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            return redirect(route('admin.home'))->with('success', 'Cache & temp files Cleared Successfully');
        })->name('cacheClear');

        Route::get('/','HomeController@index')->name('home');
        Route::get('/dashboard','HomeController@index')->name('dashboard');

        //Admin profile
        Route::get('/profile', 'HomeController@show')->name('profile');
        Route::put('/profile', 'HomeController@update');

        //Plans route(s) start
        Route::group(['namespace' => 'Plans', 'prefix' => 'plans', 'as' => 'plans.'], function() {
            //Get all plans list.
            Route::get('/', 'PlansController@index')->name('index');
            Route::post('/', 'PlansController@plans');

            //Create new plan
            Route::get('create', 'PlansController@create')->name('create');
            Route::post('create', 'PlansController@store');
            //Edit plan detail
            Route::get('{id}/edit', 'PlansController@edit')->name('edit');
            Route::put('{id}/edit', 'PlansController@update');
            //Delete plan detail.
            Route::delete('/{id}/delete', 'PlansController@destroy')->name('delete');
            //Status
            Route::get('status', 'PlansController@status')->name('status');

        });
        //Plans route(s) end

        //Plans route(s) start
        Route::group(['namespace' => 'Tips', 'prefix' => 'tips', 'as' => 'tips.'], function() {
            //Get all tips list.
            Route::get('/', 'TipsController@index')->name('index');
            Route::post('/', 'TipsController@index');

            //Create new tips
            Route::get('create', 'TipsController@create')->name('create');
            Route::post('create', 'TipsController@store');

            //Edit
            Route::get('{id}/edit', 'TipsController@edit')->name('edit');
            Route::put('{id}/edit', 'TipsController@update');

            //Delete
            Route::delete('/{id}/delete', 'TipsController@destroy')->name('delete');

            Route::get('ajax-get-plans-segment', 'TipsController@getPlansSegment')->name('getPlansSegment');
        });
        //Plans route(s) end

        //Staffmembers route(s) start
        Route::group(['namespace' => 'Staffmembers', 'prefix' => 'staff-members', 'as' => 'staffmembers.'], function() {
            //Get all staffmembers list.
            Route::get('/', 'StaffmembersController@index')->name('index');
            Route::post('/', 'StaffmembersController@getStaffMembers');
            //Create
            Route::get('create', 'StaffmembersController@create')->name('create');
            Route::post('create', 'StaffmembersController@store');
            //Edit
            Route::get('{id}/edit', 'StaffmembersController@edit')->name('edit');
            Route::put('{id}/edit', 'StaffmembersController@update');
            //Delete
            //Route::delete('/{id}/delete', 'StaffmembersController@destroy')->name('delete');
            Route::get('/{id}/delete', 'StaffmembersController@destroy')->name('delete');
            //Show staff member detail.
            Route::get('/{id}/detail', 'StaffmembersController@show')->name('show');

        });
        //Staffmembers route(s) end

        //Roles lists
        Route::group(['namespace' => 'Roles', 'prefix' => 'roles', 'as' => 'roles.'], function() {
            //Show all role lists
            Route::get('/', 'RolesController@index')->name('index');
            //Add new role list detail
            Route::get('/create', 'RolesController@create')->name('create');
            Route::post('/create', 'RolesController@store');
            //Edit role detial
            Route::get('/edit/{id}', 'RolesController@edit')->name('edit');
            Route::put('/edit/{id}', 'RolesController@update');
            //Delete role
            Route::get('/delete/{id}', 'RolesController@destroy')->name('delete');

        });

        //Site permissions route list
        Route::group(['namespace' => 'Sitepermissions', 'prefix' => 'sitepermissions', 'as' => 'sitepermissions.'], function() {
            //Manage status
            Route::get('/ajax-site-permission-status', 'SitepermissionsController@permissionstatus')->name('ajaxSitePermissionStatus');

            Route::get('/ajax-status', 'SitepermissionsController@updatestatus')->name('ajaxStatus');

            //Update all status one time action
            Route::get('/status/all/{status}/{role_id}', 'SitepermissionsController@updateallstatus')->name('updateallsitepermissionstatus');

            //Show all role lists
            Route::get('/{role_id}', 'SitepermissionsController@index')->name('index');
            //Add new role list detail
            Route::get('/create', 'SitepermissionsController@create')->name('create');
            Route::post('/create', 'SitepermissionsController@store');
            //Edit role detial
            Route::get('/edit/{id}', 'SitepermissionsController@edit')->name('edit');
            Route::put('/edit/{id}', 'SitepermissionsController@update');
            //Delete role
            Route::get('/delete/{id}', 'SitepermissionsController@destroy')->name('delete');

        });

        //User route(s) start
        Route::group(['namespace' => 'Users', 'prefix' => 'users', 'as' => 'users.'], function() {
            //Get all users list.
            Route::get('/', 'UsersController@index')->name('index');
            Route::post('/', 'UsersController@index');
            Route::get('/import', 'UsersController@import')->name('import');
            Route::post('/import', 'UsersController@memberImport');
            Route::get('/export', 'UsersController@export')->name('export');
        });
        //Users route(s) end

        //Sources route(s) start
        Route::group(['namespace' => 'Sources', 'prefix' => 'sources', 'as' => 'sources.'], function() {
            //Get all sources list.
            Route::get('/', 'SourcesController@index')->name('index');
            
            //Create
            Route::get('create', 'SourcesController@create')->name('create');
            Route::post('create', 'SourcesController@store');
            
            //Edit
            Route::get('{id}/edit', 'SourcesController@edit')->name('edit');
            Route::put('{id}/edit', 'SourcesController@update');
            
            //Delete
            Route::get('/{id}/delete', 'SourcesController@destroy')->name('delete');
            
        });
        //Sources route(s) end


        //Setting routes start
        Route::get('settings', 'Settings\SettingsController@index')->name('settings');
        Route::put('settings', 'Settings\SettingsController@update');
        //Setting routes end

        //Pages routes
        Route::group(['namespace' => 'Pages', 'prefix' => 'pages', 'as' => 'pages.'], function() {

            //Get all pages
            Route::get('/', 'PagesController@index')->name('index');
            Route::get('/create', 'PagesController@create')->name('create');
            Route::post('/create', 'PagesController@store');
            Route::get('/edit/{id}', 'PagesController@edit')->name('edit');
            Route::put('/edit/{id}', 'PagesController@update');
        });
        //Pages routes routes end


        //faqs routes
        Route::group(['namespace' => 'Faqs', 'prefix' => 'faqs', 'as' => 'faqs.'], function() {

            //Get all faqs
            Route::get('/', 'FaqsController@index')->name('index');
            Route::get('/create', 'FaqsController@create')->name('create');
            Route::post('/create', 'FaqsController@store');
            Route::get('/edit/{id}', 'FaqsController@edit')->name('edit');
            Route::put('/edit/{id}', 'FaqsController@update');
            //Delete
            Route::get('/{id}/delete', 'FaqsController@destroy')->name('delete');
        });
        //faqs routes routes end

        //Learn videos route(s)
        Route::group(['namespace' => 'Vidoes', 'prefix' => 'videos', 'as' => 'videos.'], function() {

            Route::group(['prefix' => 'categories', 'as' => 'categories.'], function() {

                //Get all videos categories
                Route::get('/', 'CategoriesController@index')->name('index');
                Route::get('/create', 'CategoriesController@create')->name('create');
                Route::post('/create', 'CategoriesController@store');
                Route::get('/edit/{id}', 'CategoriesController@edit')->name('edit');
                Route::put('/edit/{id}', 'CategoriesController@update');
                //Delete
                Route::get('/{id}/delete', 'CategoriesController@destroy')->name('delete');
            });

            //Get all videos
            Route::get('/', 'LearnVideosController@index')->name('index');
            Route::get('/create', 'LearnVideosController@create')->name('create');
            Route::post('/create', 'LearnVideosController@store');
            Route::get('/edit/{id}', 'LearnVideosController@edit')->name('edit');
            Route::put('/edit/{id}', 'LearnVideosController@update');
            //Delete
            Route::get('/{id}/delete', 'LearnVideosController@destroy')->name('delete');
        });
        //Learn videos route(s) end

        //StockEdge videos route(s)
        Route::group(['namespace' => 'Stockedgevideos', 'prefix' => 'stockedgevideos', 'as' => 'stockedgevideos.'], function() {

            //Get all StockEdge videos
            Route::get('/', 'StockedgevideosController@index')->name('index');
            Route::get('/create', 'StockedgevideosController@create')->name('create');
            Route::post('/create', 'StockedgevideosController@store');
            Route::get('/edit/{id}', 'StockedgevideosController@edit')->name('edit');
            Route::put('/edit/{id}', 'StockedgevideosController@update');
            //Delete
            Route::get('/{id}/delete', 'StockedgevideosController@destroy')->name('delete');
            
        });
        //StockEdge videos route(s) end

        //StockEdge videos route(s)
        Route::group(['prefix' => 'mutual-funds', 'as' => 'mutualfunds.'], function() {
            //Route::get('/', 'MutalfundsapiController@index')->name('index');
            Route::get('/search', 'MutalfundsapiController@search')->name('search');
            Route::get('/latest-mutual-fund-nav', 'MutalfundsapiController@latestmutualfundDetail')->name('latest-mutual-fund');
            Route::get('/mfapi', 'MutalfundsapiController@mfapi')->name('mfapi');
        });
        //StockEdge videos route(s) end

        //Support-inquiry route(s) start
        Route::group(['prefix' => 'support-inquiry', 'as' => 'support-inquiry.'], function() {
            //Get all plans list.
            Route::get('/', 'SupportenquiresController@index')->name('index');
        });
        //Support-inquiry route(s) end
		
		//Partner with us route(s) start
        Route::group(['prefix' => 'partner-with-us', 'as' => 'partner-with-us.'], function() {
            //Get all Partner with us list.
            Route::get('/', 'SupportenquiresController@partnerWithUs')->name('index');
			
        });
		
		
        //Partner with us route(s) end
		
			//referral with us route(s) start
		Route::get('/referral-userlist', 'SupportenquiresController@referral_userlist')->name('referral-userlist');
		Route::get('/referral-userdetails/{id}', 'SupportenquiresController@referral_userdetails')->name('referral-userdetails');
		Route::get('/referral-updatestatus/{id}', 'SupportenquiresController@payment_status')->name('referral-updatestatus');
		Route::get('/referral-updatestatususedby/{id}', 'SupportenquiresController@payment_status_usedcode')->name('referral-updatestatususedby');
		 //referral with us route(s) end
		
		//Send notification route(s) start
        Route::group(['namespace' => 'Notifications', 'prefix' => 'notifications', 'as' => 'notifications.'], function() {
            //Send new notification.
            Route::get('/', 'NotificationsController@index')->name('index');
            Route::post('/', 'NotificationsController@store');
        });
        //Send notification route(s) end
    });

});
/* ----------------------- Admin Routes END -------------------------------- */

//Pages routes start
Route::get('{slug}', 'Pages\PagesController@show')->name('frontPage');
//Pages routes end

Auth::routes(['register' => false, 'login' => false]);
//Auth::routes(['register' => false]);
Route::get('/home', 'HomeController@index')->name('home');




