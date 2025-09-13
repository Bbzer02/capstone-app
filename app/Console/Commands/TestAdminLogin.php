<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestAdminLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:test-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin login process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Admin Login Process...');
        
        // Test 1: Check if admin exists
        $admin = AdminUser::where('email', 'admin@ctu.edu.ph')->first();
        if (!$admin) {
            $this->error('Admin user not found!');
            return;
        }
        $this->info('✓ Admin user exists: ' . $admin->email);
        
        // Test 2: Check password
        $password = 'admin123';
        if (Hash::check($password, $admin->password)) {
            $this->info('✓ Password verification successful');
        } else {
            $this->error('✗ Password verification failed');
            return;
        }
        
        // Test 3: Try to authenticate
        if (Auth::guard('admin')->attempt(['email' => 'admin@ctu.edu.ph', 'password' => 'admin123'])) {
            $this->info('✓ Authentication successful');
            Auth::guard('admin')->logout();
        } else {
            $this->error('✗ Authentication failed');
        }
        
        $this->info('Test completed!');
    }
}
