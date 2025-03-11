<?php

namespace Database\Seeders;

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
        // Gọi UserSeeder và SenderSeeder
        $this->call([
            UserSeeder::class,
            SenderSeeder::class,
        ]);
    }
}