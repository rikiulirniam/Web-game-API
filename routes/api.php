<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('/v1')->group(function(){
    Route::post('auth/signin',[AuthController::class, 'signin'] );
    Route::post('auth/signup',[AuthController::class, 'signup'] );
});