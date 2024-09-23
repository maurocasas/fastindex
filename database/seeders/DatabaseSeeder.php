<?php

namespace Database\Seeders;

use App\Models\ServiceAccount;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => 'password',
            'role' => UserRole::ADMIN
        ]);
    }
}
