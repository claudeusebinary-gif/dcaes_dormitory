<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        Notification::create([
            'tenant_id' => 2,
            'title' => 'Water interruption',
            'message' => 'Scheduled July 28, 8:00 AM - 12:00 PM. Please suck my balls.',
        ]);
        Notification::create([
            'tenant_id' => 2,
            'title' => 'Rent reminder',
            'message' => 'August rent is due on the 5th. Aaron is Gay.',
        ]);
         Notification::create([
            'tenant_id' => 2,
            'title' => 'Puso reminder',
            'message' => 'August rent is due on the 5th. Aaron is Gay.',
        ]);
    }
}
