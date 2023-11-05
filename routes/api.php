<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/register', [AuthApiController::class, 'register']);
Route::post('auth/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/me', [AuthApiController::class, 'me']);
    Route::post('auth/updateProfilPicture', [AuthApiController::class, 'updateProfilPicture']);
    Route::post('auth/updateProfil', [AuthApiController::class, 'updateProfil']);
    Route::post('auth/updatePassword', [AuthApiController::class, 'updatePassword']);
    Route::post('auth/logout', [AuthApiController::class, 'logout']);
});


Route::get('/check', function () {
    return 'terhubung';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
