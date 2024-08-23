<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Manage users by admin user

    // Get all users records
    public function index() {
        $users = User::select('id', 'name', 'email')->get();
        $users->each(function($user) {
           $user->is_admin = (bool) $user->isAdmin();
        });
        $fields = array_keys($users->first()->toArray());
        return response()->json([
            'fields' => $fields,
            'users' => $users,
        ]);
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
            'name' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'is_admin' => 'required|boolean',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $userRole = $user->userRoles()->first();
        $userRole->is_admin = $validatedData['is_admin'];

        $user->save();
        $userRole->save();

        return response()->json(['message' => 'User updated successfully']);
    }

    // Delete specific user
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function changePassword(Request $request) {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }
        $user->password = Hash::make($validatedData['new_password']);
        $user->save();
        return response()->json(['message' => 'Password updated successfully']);
    }
}
