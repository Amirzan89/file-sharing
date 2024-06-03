<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $idUser = User::insertGetId([
            'uuid' =>  Str::uuid(),
            'name'=>'admin',
            'email'=>'Admin@gmail.com',
            'password'=>Hash::make('Admin@1234567890'),
            'role'=>'admin',
            'foto'=>'admin.jpg',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        $directory = storage_path('app/admin');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        Storage::disk('admin')->put('/admin.jpg', Crypt::encrypt(file_get_contents(database_path('seeders/image/admin.jpg'))));

        $idUser = User::insertGetId([
            'uuid' =>  Str::uuid(),
            'name'=>'user',
            'email'=>'User@gmail.com',
            'password'=>Hash::make('Admin@1234567890'),
            'role'=>'user',
            'foto'=>'user.jpeg',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        $directory = storage_path('app/user');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        Storage::disk('user')->put('/user.jpeg', Crypt::encrypt(file_get_contents(database_path('seeders/image/user.jpeg'))));

        $directory = storage_path('app/database');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}