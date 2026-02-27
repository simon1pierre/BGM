<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's users table.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'simonpierreturamyumukiza96@gmail.com'],
            [
                'first_name' => 'Simon Pierre',
                'last_name' => 'Turamyumukiza',
                'user_name' => 'simon1pierre',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
    }
}