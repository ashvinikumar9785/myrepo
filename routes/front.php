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

// Route::get('/', function () {
//     return redirect(route('society.login'));
// });


// Auth::routes(['verify' => true]);

Route::resource('projects', 'ProjectsController');

Route::get('/home', 'HomeController@index')->name('home');
Route::any('birthday-notification', 'Front\AuthController@sendBirthdayNotification'); 
Route::any('anniversary-notification', 'Front\AuthController@sendAnniversaryNotification'); 
Route::get('verify/{token}','HomeController@verify')->name('users.verify');
Route::any('reset-password/{token}','HomeController@resetPassword')->name('users.resetpassword');
Route::get('static-page/{slug}','Front\PageController@view')->name('society.pages.view');
Route::group(['prefix' => 'society','namespace'=>'Front','middleware'=>'web.guest'], function () {

    

	Route::any('login','AuthController@login')->name('society.login');	
	Route::post('forgot-password','AuthController@forgotPassword')->name('society.forgotpassword');
	Route::any('reset-password/{token}','AuthController@resetPassword')->name('society.resetpassword');
});
Route::group(['prefix' => 'society','namespace'=>'Front', 'middleware'=>['auth.user']], function () {
	Route::get('home','HomeController@index')->name('society.home')->middleware('auth.user');
  Route::get('toggle-sidebar','HomeController@toggleSidebar')->name('society.toggle-sidebar');
	Route::any('profile','HomeController@profile')->name('society.profile');
	Route::any('change-password','HomeController@changePassword')->name('society.changepassword');
	Route::get('logout','AuthController@logout')->name('society.logout');

	// Settings Route
	Route::any('settings/add/{id?}','SettingController@add')->name('society.settings.add');
	Route::any('settings/','SettingController@index')->name('society.settings.index');
	Route::any('settings/datatables','SettingController@datatables')->name('society.settings.datatables');

	// Pages Route
	Route::any('pages/add/{id?}','PageController@add')->name('society.pages.add');
	Route::any('pages/','PageController@index')->name('society.pages.index');
	Route::any('pages/datatables','PageController@datatables')->name('society.pages.datatables');
	Route::any('pages/status','PageController@status')->name('society.pages.status');

  	// Categories Route
  	Route::any('categories/add/{id?}','CategoryController@add')->name('society.categories.add');
  	Route::any('categories/','CategoryController@index')->name('society.categories.index');
  	Route::any('categories/datatables','CategoryController@datatables')->name('society.categories.datatables');
  	Route::any('categories/status','CategoryController@status')->name('society.categories.status');
  	Route::post('categories/delete/','CategoryController@delete')->name('society.categories.delete');

  	// Email Templates Route
  	Route::any('emailtemplates/add/{id?}','EmailTemplateController@add')->name('society.emailtemplates.add');
  	Route::any('emailtemplates/','EmailTemplateController@index')->name('society.emailtemplates.index');
  	Route::any('emailtemplates/datatables','EmailTemplateController@datatables')->name('society.emailtemplates.datatables');
  	Route::any('emailtemplates/status','EmailTemplateController@status')->name('society.emailtemplates.status');

  	// Users Route
  	Route::get('users','UserController@index')->name('society.users.index');
  	Route::any('users/datatables','UserController@datatables')->name('society.users.datatables');
  	Route::post('users/status','UserController@status')->name('society.users.status');
  	Route::get('users/view/{id}','UserController@view')->name('society.users.view');
  	Route::post('users/delete/','UserController@delete')->name('society.users.delete');




  	Route::any('society-member/add/{id?}','SocietyMemberController@add')->name('front.society-member.add');
  	Route::any('society-member/','SocietyMemberController@index')->name('front.society-member.index');
  	Route::any('society-member/datatables','SocietyMemberController@datatables')->name('front.society-member.datatables');
  	Route::any('society-member/status','SocietyMemberController@status')->name('front.society-member.status');
  	Route::post('society-member/delete/','SocietyMemberController@delete')->name('front.society-member.delete');
  	Route::get('society-member/view/{id}','SocietyMemberController@view')->name('front.society-member.view');
  	Route::post('society-member/import','SocietyMemberController@importExvel')->name('society.front.member.import');
  	Route::get('society-member/export','SocietyMemberController@export')->name('society.front.member.export');



  	Route::any('news-updates/add/{id?}','NewsUpdateController@add')->name('front.news-updates.add');
  	Route::any('news-updates/','NewsUpdateController@index')->name('front.news-updates.index');
  	Route::any('news-updates/datatables','NewsUpdateController@datatables')->name('front.news-updates.datatables');
  	Route::any('news-updates/status','NewsUpdateController@status')->name('front.news-updates.status');
  	Route::post('news-updates/delete/','NewsUpdateController@delete')->name('front.news-updates.delete');


  	// suggestion and complain route
  	Route::any('suggestion-feedback/add/{id?}','FeedBackController@add')->name('front.suggestion-feedback.add');
  	Route::any('suggestion-feedback/','FeedBackController@index')->name('front.suggestion-feedback.index');
  	Route::any('suggestion-feedback/datatables','FeedBackController@datatables')->name('front.suggestion-feedback.datatables');

  	Route::any('suggestion-feedback/{id}','FeedBackController@updateFeedbackstatus')->name('front.suggestion-feedback.status');

  	Route::any('suggestion-feedback-delete','FeedBackController@feedBackDelete')->name('front.suggestion-feedback.delete');

  	Route::get('suggestion-feedback/view/{id}','FeedBackController@view')->name('front.suggestion-feedback.view');


  	// Banner  Route
	Route::any('banners/add/{id?}','BannerController@add')->name('society.banners.add');
	Route::any('banners/','BannerController@index')->name('society.banners.index');
	Route::any('banners/datatables','BannerController@datatables')->name('society.banners.datatables');
	Route::any('banners/status','BannerController@status')->name('society.banners.status');


	Route::any('events/add/{id?}','EventController@add')->name('front.events.add');
  	Route::any('events/','EventController@index')->name('front.events.index');
  	Route::any('events/datatables','EventController@datatables')->name('front.events.datatables');
  	Route::any('events/status','EventController@status')->name('front.events.status');
  	Route::post('events/delete/','EventController@delete')->name('front.events.delete');
  	Route::get('events/view/{id}','EventController@view')->name('front.events.view');
  	Route::get('events/image/delete/{id}','EventController@imagedelete')->name('front.events.image.delete');



  	// Banner  Route
	Route::any('society-charges/add/{id?}','SocietyChargesController@add')->name('society.society-charges.add');
	Route::any('society-charges/','SocietyChargesController@index')->name('society.society-charges.index');
	Route::any('society-charges/datatables','SocietyChargesController@datatables')->name('society.society-charges.datatables');
	Route::any('society-charges/status','SocietyChargesController@status')->name('society.society-charges.status');



  	Route::any('transactions/','TransactionController@index')->name('front.transactions.index');
  	Route::any('transactions/datatables','TransactionController@datatables')->name('front.transactions.datatables');
  	Route::any('transactions/status','TransactionController@status')->name('front.transactions.status');
  	Route::post('transactions/delete/','TransactionController@delete')->name('front.transactions.delete');

  	Route::any('transactions/mark-complete/{id}','TransactionController@markComplete')->name('front.transactions.mark-complete');

});
