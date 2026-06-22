<?php

namespace Tests\Feature;

use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected TimeSlot $timeSlot;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@abady.com',
            'password' => bcrypt('password'),
        ]);

        // Create a default time slot
        $this->timeSlot = TimeSlot::create([
            'name' => 'Afternoon Session',
            'start_time' => '13:00:00',
            'end_time' => '16:00:00',
            'capacity' => 1,
            'is_active' => true,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_create_booking_page(): void
    {
        $response = $this->get(route('admin.bookings.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_access_create_booking_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.bookings.create'));
        $response->assertStatus(200);
        $response->assertSee('New Booking Form');
    }

    public function test_admin_can_manually_create_booking_with_valid_details(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.bookings.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'session_type' => 'Portrait Session',
            'booking_date' => '2026-07-01',
            'time_slot_id' => $this->timeSlot->id,
            'message' => 'Internal test notes',
            'status' => 'pending',
        ]);

        $response->assertRedirect(route('admin.bookings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'session_type' => 'Portrait Session',
            'booking_date' => '2026-07-01 00:00:00',
            'time_slot_id' => $this->timeSlot->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_cannot_create_booking_on_blocked_date(): void
    {
        BlockedDate::create([
            'blocked_date' => '2026-07-01',
            'reason' => 'Holiday',
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.bookings.create'))
            ->post(route('admin.bookings.store'), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'session_type' => 'Portrait Session',
                'booking_date' => '2026-07-01',
                'time_slot_id' => $this->timeSlot->id,
                'status' => 'pending',
            ]);

        $response->assertRedirect(route('admin.bookings.create'));
        $response->assertSessionHasErrors('booking_date');
        $this->assertDatabaseEmpty('bookings');
    }

    public function test_admin_cannot_create_approved_booking_if_capacity_is_exceeded(): void
    {
        // First approved booking on that date & slot
        Booking::create([
            'reference_number' => 'ABD-2026-000001',
            'name' => 'Existing Client',
            'email' => 'existing@example.com',
            'session_type' => 'Portrait Session',
            'booking_date' => '2026-07-01',
            'time_slot_id' => $this->timeSlot->id,
            'status' => 'approved',
        ]);

        // Attempting to create another APPROVED booking for the same slot (capacity is 1)
        $response = $this->actingAs($this->admin)
            ->from(route('admin.bookings.create'))
            ->post(route('admin.bookings.store'), [
                'name' => 'Second Client',
                'email' => 'second@example.com',
                'session_type' => 'Portrait Session',
                'booking_date' => '2026-07-01',
                'time_slot_id' => $this->timeSlot->id,
                'status' => 'approved',
            ]);

        $response->assertRedirect(route('admin.bookings.create'));
        $response->assertSessionHasErrors('time_slot_id');
        
        // Ensure the second booking was not saved
        $this->assertDatabaseMissing('bookings', [
            'name' => 'Second Client',
        ]);
    }

    public function test_admin_can_access_edit_booking_page(): void
    {
        $booking = Booking::create([
            'reference_number' => 'ABD-2026-999999',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'session_type' => 'Portrait Session',
            'booking_date' => '2026-07-01',
            'time_slot_id' => $this->timeSlot->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.edit', $booking->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Booking: ' . $booking->reference_number);
    }

    public function test_admin_can_update_booking_and_provide_response(): void
    {
        $booking = Booking::create([
            'reference_number' => 'ABD-2026-888888',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'session_type' => 'Portrait Session',
            'booking_date' => '2026-07-01',
            'time_slot_id' => $this->timeSlot->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.bookings.update', $booking->id), [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '987654321',
            'session_type' => 'Fashion Editorial',
            'booking_date' => '2026-07-02',
            'time_slot_id' => $this->timeSlot->id,
            'message' => 'Updated message',
            'admin_response' => 'Your request is approved and scheduled.',
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('admin.bookings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '987654321',
            'session_type' => 'Fashion Editorial',
            'booking_date' => '2026-07-02 00:00:00',
            'admin_response' => 'Your request is approved and scheduled.',
            'status' => 'approved',
        ]);
    }
}
