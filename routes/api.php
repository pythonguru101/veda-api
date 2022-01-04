<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

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

Route::post("register", [UserController::class, "register"]);

Route::post("login", [UserController::class, "login"]);

// sanctum auth middleware routes
Route::middleware(['auth:api'])->group(function () {
    Route::get("user", [UserController::class, "user"]);
    Route::get('tasks', [TaskController::class, 'index']);
    Route::post('add_task/{id?}',  [TaskController::class, "add_task"]);
    Route::post('remove_task/{id?}',  [TaskController::class, "remove_task"]);
}); 

