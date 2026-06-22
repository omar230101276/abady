<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@abady.com'],
            [
                'name' => 'Abady Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Seed default settings
        \App\Models\Setting::updateOrCreate(
            ['key' => 'booking_notifications_enabled'],
            ['value' => '1']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'bio_title'],
            ['value' => 'Documenting authentic frames & human identities.']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'bio_intro'],
            ['value' => 'I am Abady, a professional photographer and cinematographer based in Egypt, operating globally. My philosophy is rooted in minimal structures, organic lighting, and high-fashion aesthetics.']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'bio_description'],
            ['value' => 'With over a decade of capturing visual narratives, my work spans commercial editorial campaigns, minimalist street reportages, and cinematic storytelling. I collaborate with brands, designers, and curators who seek to convey organic depth and elevated aesthetics.']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'bio_image'],
            ['value' => 'images/portrait.png']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_email'],
            ['value' => 'hello@abady.com']
        );

        // Seed default time slots
        $slots = [
            [
                'name' => 'Morning Session',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'capacity' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Afternoon Session',
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'capacity' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Golden Hour Session',
                'start_time' => '17:00:00',
                'end_time' => '19:00:00',
                'capacity' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Night Studio Shoot',
                'start_time' => '20:00:00',
                'end_time' => '22:00:00',
                'capacity' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($slots as $slot) {
            \App\Models\TimeSlot::updateOrCreate(
                ['name' => $slot['name']],
                $slot
            );
        }
    }
}
