<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerExportController;
use App\Http\Controllers\CustomerImportController;
use App\Http\Controllers\MasterRecordsController;
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

Route::post('/create', [CustomerController::class, 'store']);
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
Route::put('/update/{id}', [CustomerController::class, 'update']);
Route::get('/customers', [CustomerController::class, 'search']);
Route::post('/import', [CustomerImportController::class, 'import']);
Route::get('/export', [CustomerExportController::class, 'export']);

// New table structure api
Route::get('/records', [MasterRecordsController::class, 'index']);
Route::get('/records_by_year_range', [MasterRecordsController::class, 'indexByYearRange']);
Route::post('/records/add', [MasterRecordsController::class, 'store']);
Route::delete('/records/{id}', [MasterRecordsController::class, 'destroy']);
Route::put('/records/{id}', [MasterRecordsController::class, 'update']);
Route::get('/records/search', [MasterRecordsController::class, 'search']);
