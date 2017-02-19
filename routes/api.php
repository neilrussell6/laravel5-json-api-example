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

    // primary resources

    Route::resource('users', 'UsersController', ['except' => ['destroy']]);
    Route::resource('tasks', 'TasksController');
    Route::resource('projects', 'ProjectsController');

    // sub resources
    
    Route::get('users/{user}/projects', 'UsersController@projects');
    Route::get('users/{user}/tasks', 'UsersController@tasks');

    Route::get('projects/{project}/tasks', 'ProjectsController@tasks');
    Route::get('projects/{project}/owner', 'ProjectsController@owner');
    Route::get('projects/{project}/editors', 'ProjectsController@editors');

    Route::get('tasks/{task}/project', 'TasksController@project');
    Route::get('tasks/{task}/owner', 'TasksController@owner');
    Route::get('tasks/{task}/editors', 'TasksController@editors');

    // relationships

    Route::get('users/{user}/relationships/projects', [ 'uses' => 'UsersController@projects', 'is_minimal' => true ]);
    Route::get('users/{user}/relationships/tasks', [ 'uses' => 'UsersController@tasks', 'is_minimal' => true ]);

    Route::get('projects/{project}/relationships/tasks', [ 'uses' => 'ProjectsController@tasks', 'is_minimal' => true ]);
    Route::get('projects/{project}/relationships/owner', [ 'uses' => 'ProjectsController@owner', 'is_minimal' => true ]);
    Route::get('projects/{project}/relationships/editors', [ 'uses' => 'ProjectsController@editors', 'is_minimal' => true ]);

    Route::get('tasks/{task}/relationships/project', [ 'uses' => 'TasksController@project', 'is_minimal' => true ]);
    Route::get('tasks/{task}/relationships/owner', [ 'uses' => 'TasksController@owner', 'is_minimal' => true ]);
    Route::get('tasks/{task}/relationships/editors', [ 'uses' => 'TasksController@editors', 'is_minimal' => true ]);
});
