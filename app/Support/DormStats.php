<?php

namespace App\Support;

use App\Models\User;

class DormStats
{
    public const ROOMS = 17;
    public const BEDS_PER_ROOM = 6;   // double-deck beds
    public const DECKS_PER_BED = 2;   // upper + lower

    public static function get(): array
    {
        $perRoom = self::BEDS_PER_ROOM * self::DECKS_PER_BED;   // 12
        $spaces  = self::ROOMS * $perRoom;                      // 204

        $tenants = User::where('role', 'tenant')->whereNotNull('room_number');

        $occupied = (clone $tenants)->count();

     
        $fullRooms = (clone $tenants)
            ->select('room_number')
            ->groupBy('room_number')
            ->havingRaw('count(*) >= ?', [$perRoom])
            ->get()
            ->count();

        return [
            'rooms'           => self::ROOMS,
            'available_rooms' => self::ROOMS - $fullRooms,
            'bed_spaces'      => $spaces,
            'vacancies'       => max(0, $spaces - $occupied),
        ];
    }
    public static function overview(): array
{
    $perRoom = self::BEDS_PER_ROOM * self::DECKS_PER_BED;   // 12

    // tenants per room, e.g. ['100' => 2, '101' => 12]
    $counts = User::where('role', 'tenant')
        ->whereNotNull('room_number')
        ->selectRaw('room_number, count(*) as total')
        ->groupBy('room_number')
        ->pluck('total', 'room_number');

    $full    = $counts->filter(fn ($n) => $n >= $perRoom)->count();
    $limited = $counts->filter(fn ($n) => $n >= $perRoom - 3 && $n < $perRoom)->count();  // 1-3 spaces left
    $available = self::ROOMS - $full - $limited;   // everything else, including empty rooms

    return [
        'total'     => self::ROOMS,
        'available' => $available,
        'limited'   => $limited,
        'full'      => $full,
        'percent'   => (int) round(($available + $limited) / self::ROOMS * 100),
    ];
}
}