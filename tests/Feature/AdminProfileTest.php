<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Abady Admin',
            'email' => 'admin@abady.com',
            'password' => bcrypt('password'),
        ]);

        Setting::set('bio_title', 'Original Bio Title');
        Setting::set('bio_intro', 'Original Bio Intro');
        Setting::set('bio_description', 'Original Bio Description');
        Setting::set('contact_email', 'hello@abady.com');
    }

    public function test_unauthenticated_user_cannot_access_profile_page(): void
    {
        $response = $this->get(route('admin.profile'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_access_profile_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.profile'));
        
        $response->assertStatus(200);
        $response->assertSee('Profile Settings');
        $response->assertSee('hello@abady.com');
        $response->assertSee('Original Bio Title');
    }

    public function test_admin_can_update_profile_and_contact_email(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.profile'))
            ->put(route('admin.profile.update'), [
                'name' => 'New Admin Name',
                'email' => 'newadmin@abady.com',
                'bio_title' => 'New Bio Title',
                'bio_intro' => 'New Bio Intro',
                'bio_description' => 'New Bio Description',
                'contact_email' => 'newcontact@abady.com',
                'social_instagram' => 'https://instagram.com/newuser',
            ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        // Check user model updated
        $this->admin->refresh();
        $this->assertEquals('New Admin Name', $this->admin->name);
        $this->assertEquals('newadmin@abady.com', $this->admin->email);

        // Check settings updated
        $this->assertEquals('New Bio Title', Setting::get('bio_title'));
        $this->assertEquals('newcontact@abady.com', Setting::get('contact_email'));
        $this->assertEquals('https://instagram.com/newuser', Setting::get('social_instagram'));
    }

    public function test_profile_update_validation_fails_for_invalid_emails(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.profile'))
            ->put(route('admin.profile.update'), [
                'name' => 'New Admin Name',
                'email' => 'not-an-email',
                'bio_title' => 'New Bio Title',
                'bio_intro' => 'New Bio Intro',
                'bio_description' => 'New Bio Description',
                'contact_email' => 'invalid-contact-email',
            ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHasErrors(['email', 'contact_email']);
    }
}
