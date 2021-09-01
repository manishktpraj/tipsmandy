<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace' => 'Api'], function () {
//oute::group(['namespace' => 'Api', 'prefix' => 'api'], function () {
	
	Route::get('/test-push-notification', 'TestPushNotificationController@sendTestNotification');
	
	//
	Route::get('rapidapi', 'RapidapiController@index');
	
	Route::get('rapidapi/stock/v3/get-historical-data', 'RapidapiController@getHistoricalData');
	
	Route::get('rapidapi/stock/v3/get-chart', 'RapidapiController@getChartData')->name('rapidapiStockV3GetChart');
	
	Route::get('rapidapi/stock/v3/get-options', 'RapidapiController@getOptionsData')->name('rapidapiStockV3GetOptions');
	
	Route::get('rapidapi/live-metal-prices', 'RapidapiyahoofinancesController@liveMetalPrices')->name('rapidapiliveMetalPrices');
	
	Route::get('rapidapi/market/get-chart', 'RapidapiyahoofinancesController@getMarketChartData')->name('getMarketChartData');
	
	Route::get('rapidapi/market/auto-complete', 'RapidapiyahoofinancesController@getautocompleteData')->name('getautocompleteData');
	
	Route::get('/market/auto-complete', 'FmpcloudsController@getautocompleteData')->name('getautocompleteMarketData');

	Route::get('/market/search', 'FmpcloudsController@marketStockData')->name('getMarketStockData');

	Route::get('/market/search/bse', 'FmpcloudsController@marketstockdataBse')->name('marketstockdataBse');

	Route::get('/rapidapi/market/v2/get-quotes', 'RapidapiyahoofinancesController@updategetQuotestData')->name('updategetQuotestData');

	//Register api
	Route::post('register', 'Auth\UserRegisterController@useRegister');
	
		//referral_user  api
	Route::post('referraluser', 'Users\UserHomeController@referraluser');
	
	//User Account details api
	Route::post('userbankdetails', 'Users\UserHomeController@userbankdetails');
	
	
	
	
	//Send otp for registration
	Route::post('send-otp', 'Auth\UserRegisterController@sendOtp');
	
	//Login api
	Route::post('login', 'Auth\UserLoginController@userLogin');

	//Social login api
	Route::post('sociallogin', 'SocialloginController@socialLogin');

	//Forgot password email
	Route::post('forgotpasswordemail', 'Auth\UserForgotPasswordController@sendResetLinkEmail');

	Route::group(['namespace' => 'Users', 'prefix' => 'users'], function() {	

		//Get user profile detail
		Route::get('detail', 'UserHomeController@getProfile');

		//Update user profile detail
		Route::post('update', 'UserHomeController@updateProfile');
		
		//Update device token 
		Route::post('/update-device-token', 'RegisterDeviceTokenController@update');
		
		//plan purchased
		Route::post('/plan-purchased', 'PlansController@store');
		
		//Change password 
		Route::post('/change-password', 'ChangePasswordController@update');
		
		// Notifications
		Route::get('/notifications', 'NotificationsController@index');
		
	});
	
	//Get user tip detail
	Route::post('users/tips-detail', 'Tips\TipsController@userTipDetail');

	Route::group(['namespace' => 'Plans', 'prefix' => 'plans'], function() {	

		//Get plans list
		Route::get('/', 'PlansController@index');

	});

	//Tips
	Route::group(['namespace' => 'Tips', 'prefix' => 'tips'], function() {	

		//Get plans list
		Route::get('/', 'TipsController@index');

		Route::get('/bond', 'TipsController@tipNcd'); //NCD (segment)
		Route::get('/ipo', 'TipsController@tipIpo'); //IPOs (segment)
		Route::get('/fds', 'TipsController@tipFds'); //FDs (segment)
		Route::get('/mf', 'TipsController@tipmutualFund'); //Mutual Fund (segment)

	});

	Route::group(['namespace' => 'Videos', 'prefix' => 'videos'], function() {	

		//Get videos list
		Route::get('/', 'LearnVideosController@index');

		//Get video detail
		Route::get('/detail', 'LearnVideosController@show');

	});
	
	Route::get('/stockedgevideos', 'Videos\LearnVideosController@stockedgevideos');
	
	//Get faq list
	Route::get('/faqs', 'FaqsController@index');

	Route::post('/support-inquiry', 'SupportenquiresController@store');
	
	Route::post('/partner-with-us', 'SupportenquiresController@storePartnerWithUs');

	Route::post('/lead-generate', 'Leads\LeadsController@store');

	//Get setting detail
	Route::get('generalsettings', 'SettingsController@settingdata');
	
	//Get toll free number
	Route::get('toll-free-number', 'SettingsController@tollFreeNumber');
	
	Route::group(['namespace' => 'Pages', 'prefix' => 'pages'], function() {

		//Get Pages list
		Route::get('/', 'PagesController@index');

	});
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 *
 * Using the Route::fallback method, you may define a route that will be executed when no other route matches the incoming request. Typically, unhandled requests will automatically render a "404" page via your application's exception handler. However, since you may define the fallback route within your routes/api.php file, all middleware in the web middleware group will apply to the route. You are free to add additional middleware to this route as needed:
 */  
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});
