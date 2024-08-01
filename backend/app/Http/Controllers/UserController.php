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
        $users = User::with(['userRole:is_admin'])
            ->select('id', 'name', 'email')
            ->get();
        return response()->json($users);
    }

    // Get specific user
    public function show($id) {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Update user
    public function store(Request $request) {}
}
