<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // For admin add new user
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(),
                [
                    'name' => 'required|unique:users|max:255',
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                ]);

            // Error message
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Register Failed',
                    'errors' => $validator->errors()
                ],401);
            }

            // add new user if information correct
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);
            $role = $user->userRoles()->create();

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                // Not sure the token will be used after register
                //'token' => $user->createToken('auth_token')->plainTextToken,
            ],200);

        } catch (\Throwable $exception) {
            // return 500
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ],500);
        }
    }

    // login method
    public function login(Request $request) {
        try {
            $validator = Validator::make($request->all(),
                [
                'email' => 'required|email',
                'password' => 'required',
                ]);

            // Lake of required texts
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Login Failed',
                    'errors' => $validator->errors()
                ],401);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                // Texts not match the users table
                return response()->json([
                    'status' => false,
                    'message' => 'Login Failed: email or password not found',
                ],401);
            }

            // Creat and respond user object and token (have set expiring time)
            $user = User::where('email', $request->get('email'))->first();
            $token = $user->createToken('auth_token',['*'],now()->addMinutes(120))->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successfully',
                'user' => $user,
                'token' => $token,
                'is_admin' => $user->isAdmin(),
            ],200);

        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
