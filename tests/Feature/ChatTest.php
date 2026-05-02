<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Job;
use App\Models\Applicant;
use App\Models\Message;
use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_chat_interface()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($user)
            ->get("/chat/{$applicant->id}/{$job->id}");

        $response->assertStatus(200);
        $response->assertViewIs('chat.show');
    }

    /** @test */
    public function user_can_send_message()
    {
        $user = User::factory()->create();
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($user)
            ->post('/chat/send', [
                'applicant_id' => $applicant->id,
                'job_id' => $job->id,
                'message' => 'Hello, I have a question about the position.',
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('messages', [
            'applicant_id' => $applicant->id,
            'job_id' => $job->id,
            'message' => 'Hello, I have a question about the position.',
            'sender_type' => 'applicant',
        ]);
    }

    /** @test */
    public function admin_can_view_chat_list()
    {
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();
        Applicant::factory()->count(3)->create(['job_id' => $job->id]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/chat');

        $response->assertStatus(200);
        $response->assertViewIs('admin.chat.index');
    }

    /** @test */
    public function admin_can_view_chat_with_applicant()
    {
        $admin = AdminUser::factory()->create();
        $user = User::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get("/admin/chat/{$applicant->id}/{$job->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.chat.show');
    }

    /** @test */
    public function admin_can_send_message_to_applicant()
    {
        $admin = AdminUser::factory()->create();
        $user = User::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/chat/send', [
                'applicant_id' => $applicant->id,
                'job_id' => $job->id,
                'message' => 'Thank you for your application. We will review it soon.',
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('messages', [
            'applicant_id' => $applicant->id,
            'job_id' => $job->id,
            'message' => 'Thank you for your application. We will review it soon.',
            'sender_type' => 'admin',
        ]);
    }

    /** @test */
    public function messages_can_be_retrieved_via_api()
    {
        $user = User::factory()->create();
        $admin = AdminUser::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        Message::factory()->create([
            'applicant_id' => $applicant->id,
            'job_id' => $job->id,
            'sender_type' => 'admin',
            'message' => 'Test message',
        ]);

        $response = $this->actingAs($user)
            ->post('/chat/new-messages', [
                'applicant_id' => $applicant->id,
                'job_id' => $job->id,
                'last_message_id' => 0,
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['messages']);
    }

    /** @test */
    public function user_cannot_access_other_users_chat()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $job = Job::factory()->create();
        $applicant = Applicant::factory()->create([
            'user_id' => $user2->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($user1)
            ->get("/chat/{$applicant->id}/{$job->id}");

        $response->assertStatus(403);
    }
}

