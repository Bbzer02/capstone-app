<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\Applicant;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    protected $model = Interview::class;

    public function definition(): array
    {
        return [
            'applicant_id' => Applicant::factory(),
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'type' => fake()->randomElement(['in_person', 'phone', 'video']),
            'interviewer_name' => fake()->name(),
            'notes' => fake()->paragraph(),
            'status' => 'scheduled',
        ];
    }
}

