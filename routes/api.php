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

Route::group(['middleware' => ['api'], 'namespace' => 'Api'], function () {

    Route::get('', 'ApiController@index');

    Route::resource('users', 'UsersController', ['except' => ['store', 'destroy']]);
    Route::resource('tasks', 'TasksController');
    Route::resource('projects', 'ProjectsController');
});
