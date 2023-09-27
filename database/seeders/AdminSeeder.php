<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'yousef',
            'email' => 'yousef.naseer26@gmail.com',
            'password' => bcrypt('yousef.naseer26@gmail.com'),
            'email_verified_at' => now(),
            'status' => 'مفعل',
            'roles_name' => [User::SUPER_ADMIN_ROLE],
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Assign SUPER_ADMIN_ROLE
        $user = User::create($data);
        $user->assignRole(User::SUPER_ADMIN_ROLE);
    }
}
