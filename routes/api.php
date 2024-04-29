<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('/v1')->group(function(){
    Route::post('auth/signin',[AuthController::class, 'signin'] );
    Route::post('auth/signup',[AuthController::class, 'signup'] );
    Route::middleware('auth:player')->middleware('auth:admin')->group(function(){
        Route::post('auth/signout', [AuthController::class, 'signout']);
    });
    Route::apiResource('admins', AdministratorController::class );
});