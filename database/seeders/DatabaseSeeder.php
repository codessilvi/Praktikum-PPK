<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Akun Admin System (Untuk Programmer 1)
        User::create([
            'name'     => 'Admin System',
            'email'    => 'admin@jarak.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // 2. Akun User 1 (Owner Utama Test)
        User::create([
            'name'     => 'User Satu',
            'email'    => 'user1@jarak.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);

        // 3. Akun User 2 (Untuk Kolaborator / Member)
        User::create([
            'name'     => 'User Dua',
            'email'    => 'user2@jarak.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);
    }
}