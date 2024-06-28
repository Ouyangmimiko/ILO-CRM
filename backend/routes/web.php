<?php
// This file includes the routers used by the backend
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
