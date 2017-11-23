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

Route::get('/', 'UserController@getList')->name('users')->middleware('auth');

Route::get('/projects', 'ProjectController@showList')->name('show-projects')->middleware('auth');
Route::get('/find-project/{id}', 'ProjectController@findProject')->name('find-project')->middleware('auth');
Route::post('/projects', 'ProjectController@saveProject')->name('save-project')->middleware('auth');

Route::get('/tasks', 'TaskController@showList')->name('show-tasks')->middleware('auth');
Route::post('/tasks', 'TaskController@saveTask')->name('save-task')->middleware('auth');
Route::get('/find-task/{id}', 'TaskController@findTask')->name('find-task')->middleware('auth');

Route::get('/reports', 'ReportController@showList')->name('show-reports')->middleware('auth');

Route::get('/save-mode', 'HomeController@saveMode')->name('save-mode');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
