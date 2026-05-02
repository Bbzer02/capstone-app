<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;

class DebugApplicant extends Command
{
    protected $signature = 'debug:applicant';
    protected $description = 'Debug applicant data to find format() issues';

    public function handle()
    {
        $applicant = Applicant::first();
        
        if (!$applicant) {
            $this->info('No applicants found');
            return;
        }
        
        $this->info("Applicant ID: {$applicant->id}");
        $this->info("Created at: " . ($applicant->created_at ? $applicant->created_at : 'NULL'));
        
        if ($applicant->interview) {
            $this->info("Has interview: Yes");
            $this->info("Scheduled at: " . ($applicant->interview->scheduled_at ? $applicant->interview->scheduled_at : 'NULL'));
        } else {
            $this->info("Has interview: No");
        }
        
        // Test the withDefault behavior
        $this->info("\nTesting withDefault behavior:");
        $interview = $applicant->interview;
        $this->info("Interview object exists: " . ($interview ? 'Yes' : 'No'));
        if ($interview) {
            $this->info("Interview scheduled_at: " . ($interview->scheduled_at ? $interview->scheduled_at : 'NULL'));
            $this->info("Interview status: " . ($interview->status ?? 'NULL'));
        }
    }
}