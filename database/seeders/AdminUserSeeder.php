<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        AdminUser::create([
            'name' => 'Admin',
            'email' => 'ctuAdmin@ctu.com',
            'password' => Hash::make('ctuilove@@'), // Change this!
        ]);
    }
}