<?php


use App\Http\Controllers\ProjectInvitationController;
use App\Http\Controllers\ProjectTasksController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(["middleware" => 'auth'], function(){

    Route::resource('projects', '\App\Http\Controllers\ProjectsController');

    Route::post('projects/{project}/tasks', [ProjectTasksController::class, 'store'])
          ->name('project.tasks');

    Route::patch('projects/{project}/tasks/{task}', [ProjectTasksController::class, 'update']);

    Route::post('projects/{project}/invitations', [ProjectInvitationController::class, 'store']);
});
