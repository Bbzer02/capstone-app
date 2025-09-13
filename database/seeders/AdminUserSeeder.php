<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'ctuAdmin@ctu.com',
            'password' => Hash::make('ctuilove@@'), // Change this!
        ]);
    }
}