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

Route::get('/signin', function () 
{
	$data['title'] = 'Login';
    return view('auth/login')->with($data);
});

Auth::routes();

Route::get('/', [
	'as' => 'home',
	'uses' => 'HomeController@index'
]);

//=========================== for both weitter and manager=======================//
Route::post('/update/availability', [											###
	'as'	=> 'update.availability',											###
	'uses'	=> 'UserController@postUpdateAvailability'							###
]);																				###
																				
Route::get('/profile', [														###
	'as'	=> 'user.profile',													###
	'uses'	=> 'UserController@getProfile'										###
]);	
Route::post('update/profile', [													###
	'as'	=> 'user.updateProfile',											###
	'uses'	=> 'UserController@updateProfile'									###
]);																				###

Route::post('request/payment', [												###
	'as'	=> 'user.request_payemt',											###
	'uses'	=> 'UserController@requestPayment'									###
]);																				###
//=========================== /for both weitter and manager=======================//


//================================ writter panel =================================//
Route::get('/writter/tasks', [													###
	'as'	=> 'user.tasks',													###
	'uses'	=> 'UserController@getTasks'										###
]);																				###

Route::get('/writter/archives/{type}/{month?}/{year?}', [						###
	'as'	=> 'user.archive',													###
	'uses'	=> 'UserController@getArchives'										###
]);																				###

Route::get('/writter/archive/details/{month_year}', [							###
	'as'	=> 'user.archiveDetails',											###
	'uses'	=> 'UserController@getArchiveDetails'								###
]);																				###

Route::get('/writter/payments', [												###
	'as'	=> 'user.payment',													###
	'uses'	=> 'UserController@getPayments'										###
]);	

Route::post('/writter/task/status/change', [									###
	'as'	=> 'user.pending_status_change',									###
	'uses'	=> 'UserController@postTaskStatusChange'							###
]);

Route::post('/writter/revision/task/status/change', [							###
	'as'	=> 'user.revision_pending_status_change',							###
	'uses'	=> 'UserController@postTaskRivisionStatusChange'					###
]);	

Route::post('/writter/task/submit', [											###
	'as'	=> 'user.onGoingTask_submit',										###
	'uses'	=> 'UserController@postOnGoingTaskSubmit'							###
]);																				###

//=================================== /writter panel =============================//
