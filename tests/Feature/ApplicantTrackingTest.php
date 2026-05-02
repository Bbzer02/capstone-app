<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Job;
use App\Models\Applicant;
use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicantTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_view_their_applications()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($user)->get('/applications');

        $response->assertStatus(200);
        $response->assertViewIs('applications.index');
        $response->assertViewHas('applications');
    }

    /** @test */
    public function user_can_view_application_details()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get("/applications/{$applicant->id}");

        $response->assertStatus(200);
        $response->assertViewIs('applications.show');
        $response->assertViewHas('application');
    }

    /** @test */
    public function user_cannot_view_other_users_applications()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user2->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($user1)->get("/applications/{$applicant->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_all_applicants()
    {
        $admin = AdminUser::factory()->create();
        Applicant::factory()->count(5)->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/applicants');

        $response->assertStatus(200);
        $response->assertViewIs('admin.applicants.index');
    }

    /** @test */
    public function admin_can_view_applicant_details()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/applicants/{$applicant->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.applicants.show');
    }

    /** @test */
    public function admin_can_update_applicant_status()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'job_id' => $job->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->put("/admin/applicants/{$applicant->id}/status", [
                'status' => 'shortlisted',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('applicants', [
            'id' => $applicant->id,
            'status' => 'shortlisted',
        ]);
    }

    /** @test */
    public function admin_can_reject_applicant_with_reason()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'job_id' => $job->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->put("/admin/applicants/{$applicant->id}/status", [
                'status' => 'rejected',
                'rejection_reason' => 'Does not meet requirements',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('applicants', [
            'id' => $applicant->id,
            'status' => 'rejected',
            'rejection_reason' => 'Does not meet requirements',
        ]);
    }

    /** @test */
    public function admin_can_search_applicants()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();
        Applicant::factory()->create([
            'job_id' => $job->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        Applicant::factory()->create([
            'job_id' => $job->id,
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get('/admin/applicants?search=John');

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }
}

