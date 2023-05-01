<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'Admin@gmail.com',
                'password' => Hash::make('Admin@1234567890'),
            ],[
                'name' => 'paul',
                'email' => 'jane@gmail.com',
                'password' => Hash::make('Admin@0987654321'),
            ],[
                'name' => 'terserah',
                'email' => 'terserah@gmail.com',
                'password' => Hash::make('admin@0987654321'),
            ],
        ];
        DB::table('users')->insert($data);
    }
}