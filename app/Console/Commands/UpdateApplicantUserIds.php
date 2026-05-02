<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;
use App\Models\User;

class UpdateApplicantUserIds extends Command
{
    protected $signature = 'applicants:update-user-ids';
    protected $description = 'Update existing applicants with user_id based on email';

    public function handle()
    {
        $this->info('Updating applicant user_ids...');
        
        $applicants = Applicant::whereNull('user_id')->get();
        $updated = 0;
        
        foreach ($applicants as $applicant) {
            $user = User::where('email', $applicant->email)->first();
            if ($user) {
                $applicant->update(['user_id' => $user->id]);
                $updated++;
                $this->line("Updated applicant: {$applicant->name} -> User ID: {$user->id}");
            } else {
                $this->warn("No user found for applicant: {$applicant->name} ({$applicant->email})");
            }
        }
        
        $this->info("Updated {$updated} applicants with user_id");
        return 0;
    }
}