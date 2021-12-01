<?php


use App\Http\Controllers\ProjectTasksController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(["middleware" => 'auth'], function(){
//    Route::get('projects', [ProjectsController::class, 'index']);
//    Route::get('projects/create', [ProjectsController::class, 'create']);
//    Route::post('projects', [ProjectsController::class, 'store']);
//    Route::get('projects/{project}', [ProjectsController::class, 'show']);
//    Route::get('projects/{project}/edit', [ProjectsController::class, 'edit']);
//    Route::patch('projects/{project}', [ProjectsController::class, 'update'])->name('project.update');
//    Route::delete('projects/{project}', [ProjectsController::class, 'destroy']);
    Route::resource('projects', '\App\Http\Controllers\ProjectsController');

    Route::post('projects/{project}/tasks', [ProjectTasksController::class, 'store'])
          ->name('project.tasks');

    Route::patch('projects/{project}/tasks/{task}', [ProjectTasksController::class, 'update']);
});
