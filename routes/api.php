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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::group(['namespace' => 'Api'],function($request){ 
    Route::post('reset-password', 'UserController@resetPassword'); 

    Route::post('signin', 'UserController@signin'); 
    Route::post('forgot-password', 'UserController@forgot');

    Route::post('resend-otp', 'UserController@resendOtp');
    Route::post('otp-verify', 'UserController@otpVerify');

    Route::post('check-user', 'UserController@checkUser');
    Route::get('get-country', 'UserController@getCountry');
    Route::post('signup', 'UserController@signup');
    Route::post('resend-verification','UserController@resendVerificationEmail');
    Route::post('page-content','UserController@getPageContent');
    Route::get('app-settings','UserController@userAppSetting');

    // Protected Routes
    Route::group(['middleware' => ['jwt']], function () { 
        Route::get('get-profile', 'UserController@getProfile'); 
        Route::post('logout', 'UserController@logout'); 
        Route::post('update-profile', 'UserController@updateProfile');
        Route::post('updatetoken', 'UserController@updateDeviceToken');
        Route::post('change-password', 'UserController@changePassword');
        Route::post('set-notification-status', 'UserController@setNotificationStatus'); 
        Route::get('get-notifications', 'UserController@getNotifications'); 
        Route::post('get-categories', 'UserController@getCategories'); 
        Route::post('get-settings','UserController@getSettings');
        Route::get('get-news-update','UserController@getNewsUpdate');
        Route::get('get-news-update-detail/{id}','UserController@getNewsUpdateDetail');
        Route::post('add-feedback','UserController@submitFeedback');
        Route::get('get-my-feedback','UserController@getMyFeedback');
        Route::get('get-feedback-detail/{id}','UserController@getFeedbackDetail');

        Route::get('get-event','UserController@getEventList');
        Route::get('get-event-detail/{id}','UserController@getEventDetail');

        Route::post('add-family-member','UserController@addFamilyMember');
        Route::post('update-family-member','UserController@updateFamilyMember');
        Route::post('delete-family-member','UserController@deleteFamilyMember');
        Route::get('get-family-member','UserController@getFamilyMember');

        // vehicle apis
        Route::post('add-vehicle','UserController@addVehicle');
        Route::post('update-vehicle','UserController@updateVehicle');
        Route::post('delete-vehicle','UserController@deleteVehicle');
        Route::get('get-vehicle','UserController@getVehicle');


        Route::get('get-dashboard-detail','UserController@getDashboardDetail');

        Route::get('static-page/{slug}','UserController@pageDetail');


        // visitor

        Route::post('add-visitor','UserController@addVisitor');
        Route::post('update-visitor','UserController@updateVisitor');
        Route::post('delete-visitor','UserController@deleteVisitor');
        Route::get('get-visitor','UserController@getVisitor');
        Route::get('delete-profile','UserController@deleteAccount');
        Route::get('get-payment-list','UserController@getPaymentCharges');
        Route::get('get-qr-detail','UserController@getQRDetail');
        Route::post('upload-receipt','UserController@uploadReceipt');
        Route::get('get-my-transaction','UserController@getMyTransaction');
        Route::post('send-email-verification','UserController@sendVerificationEmail');







    });
    
});

// Route::fallback(function(){
//     return response()->json(['status'=>false,'message' => 'Page Not Found'], 404);
// });
// Route::fallback(function(){
//     return response()->json(['status'=>false,'message' => 'Unn'], 401);
// });