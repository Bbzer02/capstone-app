<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminUser;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = AdminUser::first();
        
        if (!$admin) {
            $this->error('No admin user found!');
            return;
        }

        $password = 'admin123';
        $admin->password = bcrypt($password);
        $admin->save();

        $this->info('Admin password reset successfully!');
        $this->info('Email: ' . $admin->email);
        $this->info('Password: ' . $password);
    }
}
