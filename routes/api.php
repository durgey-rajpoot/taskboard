<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group(['middleware' => 'api','prefix' => 'board'], function ($router) {
    Route::get('/all_board', [BoardController::class, 'index']);
    Route::get('/{id}', [BoardController::class, 'show']);
    Route::post('/create', [BoardController::class, 'create']);
    Route::put('/update/{id}', [BoardController::class, 'update']);
    Route::delete('/delete/{id}', [BoardController::class, 'destroy']);
});

Route::group(['middleware' => 'api','prefix' => 'task'], function ($router) {
    Route::get('/all_task', [TaskController::class, 'index']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::post('/create', [TaskController::class, 'create']);
    Route::post('/assign', [TaskController::class, 'assign']);
    Route::put('/update/{id}', [TaskController::class, 'update']);
    Route::delete('/delete/{id}', [TaskController::class, 'destroy']); 
});