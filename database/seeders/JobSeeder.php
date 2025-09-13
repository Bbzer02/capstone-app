<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use Carbon\Carbon;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Assistant Professor - Computer Science',
                'description' => 'Teach computer science courses, conduct research, and mentor students in the Computer Science program. Must have a Master\'s degree in Computer Science or related field.',
                'department' => 'College of Engineering',
                'position_type' => 'Full-time',
                'salary' => 45000.00,
                'application_deadline' => Carbon::now()->addDays(30),
                'is_published' => true,
            ],
            [
                'title' => 'Administrative Assistant - Registrar Office',
                'description' => 'Assist in student records management, enrollment processes, and academic documentation. Must have excellent organizational skills and attention to detail.',
                'department' => 'Registrar Office',
                'position_type' => 'Full-time',
                'salary' => 25000.00,
                'application_deadline' => Carbon::now()->addDays(20),
                'is_published' => true,
            ],
            [
                'title' => 'Librarian - Learning Resource Center',
                'description' => 'Manage library resources, assist students and faculty with research, and maintain digital collections. Master\'s in Library Science preferred.',
                'department' => 'Learning Resource Center',
                'position_type' => 'Full-time',
                'salary' => 30000.00,
                'application_deadline' => Carbon::now()->addDays(15),
                'is_published' => true,
            ],
            [
                'title' => 'IT Support Specialist',
                'description' => 'Provide technical support for computer systems, networks, and educational technology. Must have experience with Windows, Linux, and network administration.',
                'department' => 'Information Technology Services',
                'position_type' => 'Full-time',
                'salary' => 35000.00,
                'application_deadline' => Carbon::now()->addDays(25),
                'is_published' => true,
            ],
            [
                'title' => 'Student Affairs Coordinator',
                'description' => 'Organize student activities, manage student organizations, and provide student support services. Must have experience in student development and event planning.',
                'department' => 'Student Affairs',
                'position_type' => 'Part-time',
                'salary' => 20000.00,
                'application_deadline' => Carbon::now()->addDays(10),
                'is_published' => true,
            ],
            [
                'title' => 'Research Assistant - Engineering',
                'description' => 'Assist faculty in research projects, data collection, and laboratory management. Must be enrolled in or graduated from an engineering program.',
                'department' => 'College of Engineering',
                'position_type' => 'Contract',
                'salary' => 18000.00,
                'application_deadline' => Carbon::now()->addDays(12),
                'is_published' => false,
            ],
            [
                'title' => 'Campus Security Guard',
                'description' => 'Ensure campus safety and security, monitor facilities, and assist with emergency response. Must have security training and good physical condition.',
                'department' => 'Security Services',
                'position_type' => 'Full-time',
                'salary' => 22000.00,
                'application_deadline' => Carbon::now()->addDays(18),
                'is_published' => true,
            ],
            [
                'title' => 'Maintenance Worker',
                'description' => 'Perform general maintenance and repairs on campus facilities, equipment, and infrastructure. Must have basic technical skills and tools knowledge.',
                'department' => 'Facilities Management',
                'position_type' => 'Full-time',
                'salary' => 20000.00,
                'application_deadline' => Carbon::now()->addDays(14),
                'is_published' => true,
            ],
        ];

        foreach ($jobs as $job) {
            Job::create($job);
        }
    }
}