<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate()
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

        }
    }

    public function logout(Request $request) {

    }
}
