<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'full_name' => 'Dorm Admin',
            'email' => 'admin@daces.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $tenant = User::create([
            'full_name' => 'Sample Tenant',
            'email' => 'tenant@daces.test',
            'password' => 'password',
            'role' => 'tenant',
            'room' => '101',
        ]);

        Notification::create([
            'tenant_id' => $tenant->id,
            'title' => 'Water interruption',
            'message' => 'Scheduled July 28, 8:00 AM - 12:00 PM. Please store water in advance.',
        ]);
        Notification::create([
            'tenant_id' => $tenant->id,
            'title' => 'Rent reminder',
            'message' => 'August rent is due on the 5th. Message admin if you need an extension.',
        ]);
         Notification::create([
            'tenant_id' => $tenant->id,
            'title' => 'Puso reminder',
            'message' => 'August rent is due on the 53rd. Message admin if you need an extension.',
        ]);
    }
}
