<?php

namespace Database\Factories;

use App\Models\Applicant;
use App\Models\User;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicantFactory extends Factory
{
    protected $model = Applicant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_id' => Job::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'cover_letter' => fake()->paragraphs(2, true),
            'resume_path' => 'resumes/' . fake()->uuid() . '.pdf',
            'cover_letter_path' => null,
            'transcript_path' => null,
            'certificate_path' => null,
            'portfolio_path' => null,
            'additional_documents' => null,
            'status' => 'pending',
            'rejection_reason' => null,
            'is_editable_by_user' => false,
        ];
    }
}

