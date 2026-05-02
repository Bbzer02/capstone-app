<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_jobs_list()
    {
        $admin = AdminUser::factory()->create();
        Job::factory()->count(5)->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/jobs');

        $response->assertStatus(200);
        $response->assertViewIs('admin.jobs.index');
    }

    /** @test */
    public function admin_can_view_create_job_page()
    {
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/jobs/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.jobs.create');
    }

    /** @test */
    public function admin_can_create_job()
    {
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/jobs', [
                'title' => 'Software Developer',
                'description' => 'We are looking for a skilled software developer.',
                'department' => 'IT',
                'position_type' => 'full-time',
                'salary' => '50000',
                'application_deadline' => now()->addDays(30)->format('Y-m-d'),
                'is_published' => true,
                'campus' => 'Main Campus',
                'vacancies' => 2,
                'education_requirements' => 'Bachelor degree in Computer Science',
                'experience_requirements' => '3+ years of experience',
                'eligibility_requirements' => 'Must be eligible to work',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jobs', [
            'title' => 'Software Developer',
            'department' => 'IT',
            'position_type' => 'full-time',
        ]);
    }

    /** @test */
    public function admin_can_view_edit_job_page()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get("/admin/jobs/{$job->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.jobs.edit');
    }

    /** @test */
    public function admin_can_update_job()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create([
            'title' => 'Old Title',
            'department' => 'HR',
            'application_deadline' => now()->addDays(30),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->put("/admin/jobs/{$job->id}", [
                'title' => 'New Title',
                'description' => $job->description ?? 'Job description',
                'department' => 'IT',
                'position_type' => $job->position_type ?? 'full-time',
                'salary' => $job->salary ?? '50000',
                'application_deadline' => now()->addDays(30)->format('Y-m-d'),
                'is_published' => $job->is_published,
                'campus' => $job->campus ?? 'Main Campus',
                'vacancies' => $job->vacancies ?? 1,
                'education_requirements' => $job->education_requirements ?? 'Bachelor degree',
                'experience_requirements' => $job->experience_requirements ?? 'Some experience',
                'eligibility_requirements' => $job->eligibility_requirements ?? 'Must be eligible',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'title' => 'New Title',
            'department' => 'IT',
        ]);
    }

    /** @test */
    public function admin_can_toggle_job_publish_status()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create(['is_published' => false]);

        $response = $this->actingAs($admin, 'admin')
            ->post("/admin/jobs/{$job->id}/toggle-publish");

        $response->assertRedirect();
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'is_published' => true,
        ]);
    }

    /** @test */
    public function admin_can_delete_job()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->delete("/admin/jobs/{$job->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('jobs', [
            'id' => $job->id,
        ]);
    }

    /** @test */
    public function job_creation_requires_valid_data()
    {
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/jobs', [
                'title' => '',
                'description' => '',
            ]);

        $response->assertSessionHasErrors(['title', 'description', 'department', 'position_type']);
    }
}

