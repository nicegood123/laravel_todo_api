<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


//Protected Routes
Route::middleware('auth:sanctum')->group(function () {



    //Logout User
    Route::post('/logout', [AuthController::class, 'logout']);
});
