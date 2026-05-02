<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'department' => fake()->randomElement(['IT', 'HR', 'Finance', 'Marketing', 'Operations']),
            'position_type' => fake()->randomElement(['full-time', 'part-time', 'contract', 'remote']),
            'salary' => fake()->randomFloat(2, 20000, 100000),
            'application_deadline' => fake()->dateTimeBetween('now', '+3 months'),
            'is_published' => true,
            'item_number' => fake()->numerify('ITEM-####'),
            'campus' => fake()->randomElement(['Main Campus', 'North Campus', 'South Campus']),
            'vacancies' => fake()->numberBetween(1, 5),
            'education_requirements' => fake()->sentence(),
            'experience_requirements' => fake()->sentence(),
            'training_requirements' => fake()->sentence(),
            'eligibility_requirements' => fake()->sentence(),
        ];
    }
}

