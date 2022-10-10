<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Test User',
            'surname' => 'Smith',
            'email' => 'test@example.com',
            'phone' => 123456789,
            'google' => false,
            'active' => true,
            'password' => bcrypt('password')
        ]);
    }
}
