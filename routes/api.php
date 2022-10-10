<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use  App\Http\Controllers\Api\AuthController;
use  App\Http\Controllers\UserController;
use  App\Http\Controllers\AdController;


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

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::get('/user', [UserController::class, 'getUser'])->middleware('auth');

Route::post('/ads', [AdController::class, 'store'])->middleware('auth');
Route::get('/ads', [AdController::class, 'view']);
