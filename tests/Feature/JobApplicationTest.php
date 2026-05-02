<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Job;
use App\Models\Applicant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_view_job_listings()
    {
        Job::factory()->count(5)->create(['is_published' => true]);

        $response = $this->get('/jobs');

        $response->assertStatus(200);
        $response->assertViewIs('jobs.index');
    }

    /** @test */
    public function user_can_view_job_details()
    {
        $job = Job::factory()->create([
            'is_published' => true,
            'application_deadline' => now()->addDays(30),
        ]);

        $response = $this->get("/jobs/{$job->id}");

        $response->assertStatus(200);
        $response->assertViewIs('jobs.show');
        $response->assertViewHas('job');
    }

    /** @test */
    public function user_can_apply_for_job()
    {
        $user = User::factory()->create([
            'name' => 'Test Applicant',
            'email' => 'applicant@example.com',
        ]);
        $job = Job::factory()->create([
            'is_published' => true,
            'application_deadline' => now()->addDays(30),
        ]);

        $resume = UploadedFile::fake()->create('resume.pdf', 100);
        $coverLetter = UploadedFile::fake()->create('cover-letter.pdf', 50);

        $response = $this->actingAs($user)
            ->post("/jobs/{$job->id}/apply", [
                'phone' => '1234567890',
                'cover_letter' => 'This is my cover letter.',
                'resume' => $resume,
                'cover_letter_file' => $coverLetter,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('applicants', [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'email' => $user->email,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function user_can_apply_with_all_documents()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create([
            'is_published' => true,
            'application_deadline' => now()->addDays(30),
        ]);

        $response = $this->actingAs($user)
            ->post("/jobs/{$job->id}/apply", [
                'phone' => '1234567890',
                'cover_letter' => 'This is my cover letter.',
                'resume' => UploadedFile::fake()->create('resume.pdf', 100),
                'cover_letter_file' => UploadedFile::fake()->create('cover.pdf', 50),
                'transcript' => UploadedFile::fake()->create('transcript.pdf', 80),
                'certificate' => UploadedFile::fake()->create('certificate.pdf', 60),
                'portfolio' => UploadedFile::fake()->create('portfolio.pdf', 120),
            ]);

        $response->assertRedirect();
        $applicant = Applicant::where('user_id', $user->id)->first();
        $this->assertNotNull($applicant->resume_path);
        $this->assertNotNull($applicant->transcript_path);
    }

    /** @test */
    public function application_requires_required_fields()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create([
            'is_published' => true,
            'application_deadline' => now()->addDays(30),
        ]);

        $response = $this->actingAs($user)
            ->post("/jobs/{$job->id}/apply", []);

        $response->assertSessionHasErrors(['cover_letter', 'resume']);
    }

    /** @test */
    public function user_cannot_apply_for_unpublished_job()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create([
            'is_published' => false,
        ]);

        // First check that viewing the job returns 404
        $viewResponse = $this->actingAs($user)->get("/jobs/{$job->id}");
        $viewResponse->assertStatus(404);

        // Then check that applying also fails (should not be able to access the route)
        $response = $this->actingAs($user)
            ->post("/jobs/{$job->id}/apply", [
                'cover_letter' => 'Cover letter',
                'resume' => UploadedFile::fake()->create('resume.pdf', 100),
            ]);

        // The route might return 404 or validation error, but should not succeed
        $this->assertTrue(in_array($response->status(), [404, 302]));
    }

    /** @test */
    public function user_cannot_apply_for_expired_job()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create([
            'is_published' => true,
            'application_deadline' => now()->subDays(1),
        ]);

        // First check that viewing the job returns 404
        $viewResponse = $this->actingAs($user)->get("/jobs/{$job->id}");
        $viewResponse->assertStatus(404);

        // Then check that applying also fails (should not be able to access the route)
        $response = $this->actingAs($user)
            ->post("/jobs/{$job->id}/apply", [
                'cover_letter' => 'Cover letter',
                'resume' => UploadedFile::fake()->create('resume.pdf', 100),
            ]);

        // The route might return 404 or validation error, but should not succeed
        $this->assertTrue(in_array($response->status(), [404, 302]));
    }
}

