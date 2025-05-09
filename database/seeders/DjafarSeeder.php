<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DjafarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah user sudah ada
        $user = User::where('email', 'djafar@mail.com')->first();
        
        if (!$user) {
            User::create([
                'name' => 'Djafar',
                'email' => 'djafar@mail.com',
                'password' => Hash::make('Plagiator13!!!'),
                'role' => 'admin'
            ]);
        }
    }
} 