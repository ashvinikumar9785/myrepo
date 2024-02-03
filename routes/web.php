<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
Route::get('/link-cache', function () {
     Artisan::call('cache:clear');
   Artisan::call('route:clear');
   dd("cleard");
});
Route::get('/', function () {
    return redirect(route('admin.login'));
});

    require_once __DIR__.'/front.php';

// Auth::routes(['verify' => true]);
Route::resource('projects', 'ProjectsController');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('verify/{token}','HomeController@verify')->name('users.verify');
Route::any('reset-password/{token}','HomeController@resetPassword')->name('users.resetpassword');
// Route::get('static/{slug}','Admin\PageController@view')->name('admin.pages.view');
Route::group(['prefix' => 'admin','namespace'=>'Admin','middleware'=>'admin.guest'], function () {
	Route::any('login','AuthController@login')->name('admin.login');	
	Route::post('forgot-password','AuthController@forgotPassword')->name('admin.forgotpassword');
	Route::any('reset-password/{token}','AuthController@resetPassword')->name('admin.resetpassword');
});
Route::group(['prefix' => 'admin','namespace'=>'Admin','middleware'=>'admin'], function () {
	Route::get('home','HomeController@index')->name('admin.home');
  Route::get('toggle-sidebar','HomeController@toggleSidebar')->name('admin.toggle-sidebar');
	Route::any('profile','HomeController@profile')->name('admin.profile');
	Route::any('change-password','HomeController@changePassword')->name('admin.changepassword');
	Route::get('logout','AuthController@logout')->name('admin.logout');

	// Settings Route
	Route::any('settings/add/{id?}','SettingController@add')->name('admin.settings.add');
	Route::any('settings/','SettingController@index')->name('admin.settings.index');
	Route::any('settings/datatables','SettingController@datatables')->name('admin.settings.datatables');
  Route::any('app/setting/{id?}','SettingController@appSetting')->name('app.setting');


	// Pages Route
	// Route::any('pages/add/{id?}','PageController@add')->name('admin.pages.add');
	// Route::any('pages/','PageController@index')->name('admin.pages.index');
	// Route::any('pages/datatables','PageController@datatables')->name('admin.pages.datatables');
	// Route::any('pages/status','PageController@status')->name('admin.pages.status');

  	// Categories Route
  	Route::any('categories/add/{id?}','CategoryController@add')->name('admin.categories.add');
  	Route::any('categories/','CategoryController@index')->name('admin.categories.index');
  	Route::any('categories/datatables','CategoryController@datatables')->name('admin.categories.datatables');
  	Route::any('categories/status','CategoryController@status')->name('admin.categories.status');
  	Route::post('categories/delete/','CategoryController@delete')->name('admin.categories.delete');

  	// Email Templates Route
  	Route::any('emailtemplates/add/{id?}','EmailTemplateController@add')->name('admin.emailtemplates.add');
  	Route::any('emailtemplates/','EmailTemplateController@index')->name('admin.emailtemplates.index');
  	Route::any('emailtemplates/datatables','EmailTemplateController@datatables')->name('admin.emailtemplates.datatables');
  	Route::any('emailtemplates/status','EmailTemplateController@status')->name('admin.emailtemplates.status');

  	// Users Route
  	Route::get('users','UserController@index')->name('admin.users.index');
  	Route::any('users/datatables','UserController@datatables')->name('admin.users.datatables');
  	Route::post('users/status','UserController@status')->name('admin.users.status');
  	Route::get('users/view/{id}','UserController@view')->name('admin.users.view');
  	Route::post('users/delete/','UserController@delete')->name('admin.users.delete');




  		// Categories Route
  	Route::any('society-owner/add/{id?}','SocietyOwnerController@add')->name('admin.society-owner.add');
  	Route::any('society-owner/','SocietyOwnerController@index')->name('admin.society-owner.index');
  	Route::any('society-owner/datatables','SocietyOwnerController@datatables')->name('admin.society-owner.datatables');
  	Route::any('society-owner/status','SocietyOwnerController@status')->name('admin.society-owner.status');
  	Route::post('society-owner/delete/','SocietyOwnerController@delete')->name('admin.society-owner.delete');
});
