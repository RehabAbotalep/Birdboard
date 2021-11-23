<?php

use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectTasksController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(["middleware" => 'auth'], function(){
    Route::get('projects', [ProjectsController::class, 'index']);
    Route::post('projects', [ProjectsController::class, 'store']);
    Route::get('projects/{project}', [ProjectsController::class, 'show']);
    Route::patch('projects/{project}', [ProjectsController::class, 'update'])->name('project.update');;
    Route::post('projects/{project}/tasks', [ProjectTasksController::class, 'store'])
    ->name('project.tasks');
    Route::patch('projects/{project}/tasks/{task}', [ProjectTasksController::class, 'update']);
});
