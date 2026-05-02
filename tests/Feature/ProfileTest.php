<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_view_their_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
        $response->assertViewHas('user');
    }

    /** @test */
    public function user_can_view_profile_edit_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    }

    /** @test */
    public function user_can_update_their_profile()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->actingAs($user)
            ->put('/profile', [
                'name' => 'New Name',
                'email' => $user->email,
                'phone' => '9876543210',
                'bio' => 'Updated bio',
                'address' => 'New Address',
            ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'phone' => '9876543210',
        ]);
    }

    /** @test */
    public function user_can_upload_profile_picture()
    {
        $user = User::factory()->create();
        
        // Create a fake image file using create method with proper mime type
        // This works even without GD extension
        $file = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)
            ->put('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $file,
            ]);

        $response->assertRedirect(route('profile.show'));
        $user->refresh();
        $this->assertNotNull($user->profile_picture);
        Storage::disk('public')->assertExists($user->profile_picture);
    }

    /** @test */
    public function user_can_update_linkedin_and_portfolio()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'linkedin_url' => 'https://linkedin.com/in/testuser',
                'portfolio_url' => 'https://portfolio.testuser.com',
            ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'linkedin_url' => 'https://linkedin.com/in/testuser',
            'portfolio_url' => 'https://portfolio.testuser.com',
        ]);
    }

    /** @test */
    public function user_can_update_skills_and_experience()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'skills' => 'PHP, Laravel, JavaScript',
                'experience' => '5 years of web development',
                'education' => 'Bachelor of Computer Science',
            ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'skills' => 'PHP, Laravel, JavaScript',
        ]);
    }
}

