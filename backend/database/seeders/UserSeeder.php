<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // creat admin user
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'iamAdmin_user',
            'email_verified_at' => now(),
        ]);
        $role = $user->userRoles()->create([
            'is_admin' => true,
        ]);
    }
}
