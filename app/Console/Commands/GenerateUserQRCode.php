<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateUserQRCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:generate-qr {--email= : Generate QR code for specific user email} {--all : Generate QR codes for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR codes for users (for ID cards)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $all = $this->option('all');
        
        if ($all) {
            // Generate QR codes for all users without one
            $users = \App\Models\User::whereNull('qr_code')->orWhere('qr_code', '')->get();
            
            if ($users->isEmpty()) {
                $this->info('All users already have QR codes.');
                return 0;
            }
            
            $this->info("Generating QR codes for {$users->count()} users...");
            
            foreach ($users as $user) {
                $this->generateQRCode($user);
            }
            
            $this->info('✓ All QR codes generated successfully!');
            return 0;
        }
        
        if ($email) {
            $user = \App\Models\User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email '{$email}' not found.");
                return 1;
            }
            
            if ($user->qr_code) {
                if (!$this->confirm("User already has QR code: {$user->qr_code}. Generate new one?")) {
                    return 0;
                }
            }
            
            $this->generateQRCode($user);
            $this->info("✓ QR code generated for {$user->name} ({$user->email})");
            $this->info("  QR Code: {$user->qr_code}");
            return 0;
        }
        
        $this->error('Please specify --email=user@example.com or --all');
        return 1;
    }
    
    private function generateQRCode($user)
    {
        // Generate unique QR code based on user ID and email
        // Format: CTU-{user_id}-{hash}
        $hash = substr(md5($user->email . $user->id . config('app.key')), 0, 8);
        $qrCode = 'CTU-' . $user->id . '-' . strtoupper($hash);
        
        $user->qr_code = $qrCode;
        $user->save();
        
        $this->line("  Generated QR code for {$user->name}: {$qrCode}");
    }
}
