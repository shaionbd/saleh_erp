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
]);

Route::get('/profile', [														###
	'as'	=> 'user.profile',													###
	'uses'	=> 'UserController@getProfile'										###
]);

Route::post('update/profile', [													###
	'as'	=> 'user.updateProfile',											###
	'uses'	=> 'UserController@updateProfile'									###
]);

Route::get('/manager/team', [													###
	'as'	=> 'user.managerTeam',												###
	'uses'	=> 'UserController@getManagerTeam'									###
]);

Route::get('/manager/edit/team/{id}', [											###
	'as'	=> 'user.managerEditTeam',											###
	'uses'	=> 'UserController@getManagerEditTeam'								###
]);																				###

Route::post('/manager/update/team', [											###
	'as'	=> 'user.managerUpdateTeam',										###
	'uses'	=> 'UserController@updateManagerTeam'								###
]);																				###

Route::get('/manager/delete/team/{id}', [										###
	'as'	=> 'user.managerDeleteTeam',										###
	'uses'	=> 'UserController@deleteManagerTeam'								###
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

Route::post('/writter/task/pending', [											###
	'as'	=> 'task.pending',													###
	'uses'	=> 'UserController@getPendingTask'									###
]);																				###

Route::post('/writter/task/ongoing', [											###
	'as'	=> 'task.on_going',													###
	'uses'	=> 'UserController@getOnGoingTask'									###
]);

Route::post('/writer/time-extend/request', [
	'as'	=> 'task.date_extend',												###
	'uses'	=> 'UserController@postOnGoingTaskDateExtend'						###
]);																				###

Route::post('/writter/task/submitted', [										###
	'as'	=> 'task.submitted',												###
	'uses'	=> 'UserController@getSubmittedTask'								###
]);

Route::post('/writter/task/review', [											###
	'as'	=> 'task.review',													###
	'uses'	=> 'UserController@getReviewTask'									###
]);

//=================================== /writter panel =============================//

//=================================== manager panel ===============================//
Route::get('/manager/projects', [												###
	'as'	=> 'manager.projects',												###
	'uses'	=> 'ManagerController@getProjects'									###
]);

Route::get('/manager/make-project/complete/{id}', [								###
	'as'	=> 'item_create.complete',											###
	'uses'	=> 'ManagerController@makeProjectComplete'							###
]);

Route::post('/manager/project/pending', [										###
	'as'	=> 'project.pending',												###
	'uses'	=> 'ManagerController@getPendingProject'							###
]);

Route::post('/manager/create/item', [											###
	'as' => 'create.item',														###
	'uses' => 'ManagerController@postCreateItem'								###
]);

Route::get('/manager/tasks', [													###
	'as'	=> 'manager.tasks',													###
	'uses'	=> 'ManagerController@getTasks'										###
]);

Route::post('/manager/item/status/change', [									###
	'as'	=> 'manager.pending_status_change',									###
	'uses'	=> 'ManagerController@postItemStatusChange'  						###
]);

Route::post('/manager/item/submission/status/change', [							###
	'as'	=> 'manager.submission_status_change',								###
	'uses'	=> 'ManagerController@postItemSubmissionStatusChange'  				###
]);

Route::post('/manager/item/pending', [											###
	'as'	=> 'item.pending',													###
	'uses'	=> 'ManagerController@getPendingItem'								###
]);

Route::post('/manager/task/assign', [											###
	'as'	=> 'task.assign',													###
	'uses'	=> 'ManagerController@getAssignTask'								###
]);
Route::post('/manager/task/assign/send', [										###
	'as'	=> 'task.assign_send',												###
	'uses'	=> 'ManagerController@postAssignTask'								###
]);

Route::post('/manager/writter/task/pending', [									###
	'as'	=> 'task.writter_pending',											###
	'uses'	=> 'ManagerController@getWritterPendingTask'						###
]);

Route::post('/manager/task/ongoing', [											###
	'as'	=> 'task.manager_on_going',											###
	'uses'	=> 'ManagerController@getOnGoingTask'								###
]);

Route::post('/manager/task/review', [											###
	'as'	=> 'task.manager_review',											###
	'uses'	=> 'ManagerController@getReviewTask'								###
]);

Route::post('/manager/task/submitted', [										###
	'as'	=> 'task.manager_submitted',										###
	'uses'	=> 'ManagerController@getSubmittedTask'								###
]);

Route::post('/manager/task/revision', [											###
	'as'	=> 'task.manager_revision',											###
	'uses'	=> 'ManagerController@getRevisionTask'								###
]);

Route::post('/manager/item/submission/file', [									###
	'as'	=> 'task.manager_file_upload',										###
	'uses'	=> 'ManagerController@postTaskFile'									###
]);
//=================================== /manager panel ===============================//

//=================================== admin panel ===============================//
Route::get('/admin/tasks', [													###
	'as'	=> 'admin.tasks',													###
	'uses'	=> 'AdminController@getTasks'										###
]);

Route::post('/admin/project/pending', [											###
	'as'	=> 'project.admin_pending',											###
	'uses'	=> 'AdminController@getPendingProject'								###
]);

Route::post('/admin/project/create', [											###
	'as'	=> 'admin.create_package',											###
	'uses'	=> 'AdminController@postCreateProject'								###
]);	

Route::post('/admin/task/status/change', [										###
	'as'	=> 'admin.pending_status_change',									###
	'uses'	=> 'AdminController@postTaskStatusChange'							###
]);
Route::post('/admin/project/process', [											###
	'as'	=> 'project.admin_process',											###
	'uses'	=> 'AdminController@getProcessProject'								###
]);

Route::post('/admin/project/assign/to/manager', [								###
	'as'	=> 'project.admin_project_assigng',									###
	'uses'	=> 'AdminController@postAssignProject'								###
]);

Route::post('/admin/create/item', [												###
	'as' => 'admin.create_item',												###
	'uses' => 'AdminController@postCreateItem'									###
]);

Route::post('/admin/assigned/item', [											###
	'as'	=> 'admin.assigned_item',											###
	'uses'	=> 'AdminController@postAssignedItem'								###
]);

Route::post('/admin/assigned/project', [										###
	'as'	=> 'admin.assigned_project',										###
	'uses'	=> 'AdminController@postAssignedProject'							###
]);

Route::post('/admin/task/re-assign/send', [											###
	'as'	=> 'admin.re_assigned_item',											###
	'uses'	=> 'AdminController@postReAssignedItem'								###
]);

//=================================== /admin panel ===============================//