<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Announcement::firstOrCreate([
    'message' => 'Water interruption scheduled on July 28, 8:00 AM – 12:00 PM. Please store water in advance.',
]);

Announcement::firstOrCreate([
    'message' => 'Friendly reminder that August rent is due on the 5th. Message admin if you need an extension.',
]);
    }
}
