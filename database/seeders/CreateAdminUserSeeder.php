<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Create user
        $user = User::create([
            'name' => 'Super', 
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'new' => User::NOT_NEW_USER
        ]);
        //Create user role
        UserRole::create([
            'user_id' => $user->id, 
            'role' => UserRole::ROLE_SUPER_ADMIN,
        ]);
    }
}
