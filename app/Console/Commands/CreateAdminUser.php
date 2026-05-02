<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('Admin name');
        $email = $this->argument('email') ?? $this->ask('Admin email');
        $password = $this->argument('password') ?? $this->secret('Admin password');
        
        // Check if admin already exists
        $existing = \App\Models\AdminUser::where('email', $email)->first();
        if ($existing) {
            if (!$this->confirm("Admin with email '{$email}' already exists. Update password?")) {
                return 0;
            }
            // Use model's setter which will hash automatically
            $existing->password = $password;
            $existing->save();
            $this->info("✓ Admin password updated for {$email}");
            return 0;
        }
        
        // Create new admin - model will hash password automatically
        \App\Models\AdminUser::create([
            'name' => $name,
            'email' => $email,
            'password' => $password, // Model's setPasswordAttribute will hash it
        ]);
        
        $this->info("✓ Admin user created successfully!");
        $this->info("  Name: {$name}");
        $this->info("  Email: {$email}");
        $this->info("  Password: {$password}");
        
        return 0;
    }
}
