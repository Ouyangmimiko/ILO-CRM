<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdminRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
//Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/loginStatus', [AuthController::class, 'checkLoginStatus']);
});

// UserController
//Route::get('/users', [UserController::class, 'index']);
//Route::get('/users/{id}', [UserController::class, 'show']);
//Route::post('/users/add', [UserController::class, 'store']);
//Route::put('/users/{id}', [UserController::class, 'update']);
//Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::middleware(['auth:sanctum', EnsureAdminRole::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users/add', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
