<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Manage users by admin user

    // Get all users records
    public function index() {
        $users = User::select('id', 'name', 'email')->get();
        $users->each(function($user) {
           $user->is_admin = (bool) $user->isAdmin();
        });
        return response()->json($users);
    }

    // Get specific user
    public function show($id) {
        $user = User::findOrFail($id);
        $user->is_admin = (bool) $user->isAdmin();
        return response()->json($user);
    }

    // Add new user
    public function store(Request $request) {
        $auth = new AuthController();
        return $auth->register($request);
    }

    // Update specific user
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([

        ]);

    }

    // Delete specific user
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
