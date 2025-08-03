<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'One',
            'email' => 'admin@example.com',
            'phone' => '+918709613425',
            'gender' => 'Male',
            'age' => 24,
            'role_id' => 1,
            'password' => 'Admin@123'
        ]);
    }
}
