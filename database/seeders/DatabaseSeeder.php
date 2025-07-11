<?php

namespace Database\Seeders;

use App\Models\Borrow;
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
        User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => bcrypt('123123123'),
        //     'phone_number' => '081234567890',
        // ]);


        $this->call(BookSeeder::class);
        Borrow::factory(800)->create();
    }
}
