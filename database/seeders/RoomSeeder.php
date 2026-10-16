<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(100, 116) as $number) {
            Room::firstOrCreate(
                ['room_number' => (string) $number],
                [
                    'sharing_type' => 'Bed Sharing',
                    'bed_type' => 'Double Deck',
                    'total_beds' => 6,
                    'price_per_bed' => 2000,
                ]
            );
        }
    }
}