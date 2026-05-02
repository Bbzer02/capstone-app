<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Applicant;
use App\Models\Job;
use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'applicant_id' => Applicant::factory(),
            'job_id' => Job::factory(),
            'sender_type' => fake()->randomElement(['admin', 'applicant']),
            'admin_id' => null,
            'message' => fake()->sentence(),
            'is_read' => false,
            'read_at' => null,
        ];
    }

    public function fromAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_type' => 'admin',
            'admin_id' => AdminUser::factory(),
        ]);
    }

    public function fromApplicant(): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_type' => 'applicant',
            'admin_id' => null,
        ]);
    }
}

