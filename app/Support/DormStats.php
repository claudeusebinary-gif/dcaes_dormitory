<?php

namespace App\Support;

use App\Models\Room;
use App\Models\User;


class DormStats
{
    public const ROOMS = 17;
    public const BEDS_PER_ROOM = 6;   
    public const DECKS_PER_BED = 2;   
    public const ROOM_TYPE     = 'Twin Sharing';
    public const BED_TYPE      = 'Double Deck';
    public const PRICE_PER_BED = 2000;   
    public static function get(): array
    {
        $perRoom = self::BEDS_PER_ROOM * self::DECKS_PER_BED;   
        $spaces  = self::ROOMS * $perRoom;                      

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
        $perRoom = self::BEDS_PER_ROOM * self::DECKS_PER_BED;   

        $counts = User::where('role', 'tenant')
            ->whereNotNull('room_number')
            ->selectRaw('room_number, count(*) as total')
            ->groupBy('room_number')
            ->pluck('total', 'room_number');

        $full      = $counts->filter(fn ($n) => $n >= $perRoom)->count();
        $limited   = $counts->filter(fn ($n) => $n >= $perRoom - 3 && $n < $perRoom)->count();
        $available = self::ROOMS - $full - $limited;   

        return [
            'total'     => self::ROOMS,
            'available' => $available,
            'limited'   => $limited,
            'full'      => $full,
            'percent'   => (int) round(($available + $limited) / self::ROOMS * 100),
        ];
    }
public static function listing(): array
{
    $rooms = Room::all();

   
    $counts = User::where('role', 'tenant')
        ->whereNotNull('room_number')
        ->selectRaw('room_number, count(*) as total')
        ->groupBy('room_number')
        ->pluck('total', 'room_number');

   
    $full = $rooms->filter(function ($room) use ($counts) {
        $capacity = $room->total_beds * self::DECKS_PER_BED;
        return ($counts[$room->room_number] ?? 0) >= $capacity;
    })->count();

    return [
        'listed' => $rooms->count(),
        'full'   => $full,
        'open'   => $rooms->count() - $full,        
        'lowest' => (float) ($rooms->min('price_per_bed') ?? 0),
    ];
}
    
public static function bedTitle(\Illuminate\Support\Collection $occupants): string
{
    if ($occupants->count() >= self::DECKS_PER_BED) {
        return 'Lower & Upper';              
    }

    $decks = $occupants->pluck('bed_deck')->map(fn ($d) => strtolower((string) $d));

    if ($decks->contains('upper')) return 'Lower';   
    if ($decks->contains('lower')) return 'Upper';  

    return 'Lower & Upper';                  
}
   public static function rooms(): array
{
    $perRoom = self::BEDS_PER_ROOM * self::DECKS_PER_BED;   

    $byRoom = User::where('role', 'tenant')
        ->whereNotNull('room_number')
        ->get(['room_number', 'bed_number', 'bed_deck'])
        ->groupBy('room_number');

    $rooms = [];

    foreach (range(100, 116) as $n) {                       
        $people   = $byRoom->get($n, collect());
        $occupied = $people->count();

        $beds = [];
       for ($b = 1; $b <= self::BEDS_PER_ROOM; $b++) {
    $bedPeople = $people->where('bed_number', $b);
    $taken     = min($bedPeople->count(), self::DECKS_PER_BED);
    $beds[] = [
        'number' => $b,
        'taken'  => $taken,
        'title'  => self::bedTitle($bedPeople),
        'status' => $taken >= self::DECKS_PER_BED ? 'occupied' : 'available',
    ];
}

        $rooms[$n] = [
            'occupied'   => $occupied,
            'capacity'   => $perRoom,
            'free'       => max(0, $perRoom - $occupied),
            'status'     => $occupied >= $perRoom      ? 'full'
                          : ($occupied >= $perRoom - 3 ? 'limited' : 'available'),
            'type'       => self::ROOM_TYPE,
            'bed_type'   => self::BED_TYPE,
            'total_beds' => self::BEDS_PER_ROOM,
            'price'      => self::PRICE_PER_BED,
            'beds'       => $beds,
        ];
    }

    return $rooms;
}
}