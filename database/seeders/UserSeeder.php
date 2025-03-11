<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Nguyễn Đức Thắng',
            'email' => 'admin@gmail.com', // Email
            'password' => bcrypt('Aa123456@'), // Mật khẩu đã băm
            'role' => User::ROLE_ADMIN, // Gán vai trò
        ]);

        User::create([
            'name' => 'Nguyễn Công Đạt',
            'email' => 'user@gmail.com', 
            'password' => bcrypt('Aa123456@'), 
            'role' => User::ROLE_USER, 
        ]);
    }
}