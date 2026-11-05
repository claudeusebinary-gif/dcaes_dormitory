<?php

namespace App\Support;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DormStats
{
    public const RESERVATION_FEE = 50;
public const SECURITY_FEE    = 50;
public const CLEAN_FEE       = 20;
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
public static function fees(): array
{
    return [
        'reservation' => self::RESERVATION_FEE,
        'security'    => self::roomSettings()['deposit'] ? self::SECURITY_FEE : 0,
        'clean'       => self::CLEAN_FEE,
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
public static function featuredRooms(int $limit = 2): \Illuminate\Support\Collection
{
    $counts = User::where('role', 'tenant')
        ->whereNotNull('room_number')
        ->selectRaw('room_number, count(*) as total')
        ->groupBy('room_number')
        ->pluck('total', 'room_number');

    return Room::where('under_maintenance', false)
        ->orderBy('room_number')
        ->get()
        ->map(function ($room) use ($counts) {
            $capacity = $room->total_beds * self::DECKS_PER_BED;
            $occupied = (int) ($counts[$room->room_number] ?? 0);

            return [
                'number' => $room->room_number,
                'beds'   => $room->total_beds,
                'decks'  => self::DECKS_PER_BED,
                'price'  => (float) $room->price_per_bed,
                'free'   => max(0, $capacity - $occupied),
            ];
        })
        ->filter(fn ($room) => $room['free'] > 0)
        ->take($limit)
        ->values();
}
    public const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public static function setting(string $key, $default = null)
    {
        if (! Schema::hasTable('dorm_settings')) {
            return $default;
        }
        $raw = DB::table('dorm_settings')->where('key', $key)->value('value');
        if ($raw === null) {
            return $default;
        }
        $decoded = json_decode($raw, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $default;
    }

    public static function putSetting(string $key, $value): void
    {
        DB::table('dorm_settings')->updateOrInsert(
            ['key' => $key],
            ['value' => json_encode($value), 'updated_at' => now()]
        );
    }

    public static function frontDesk(): array
    {
        $saved = self::setting('front_desk_hours', []);
        $saved = is_array($saved) ? $saved : [];
        $out = [];
        foreach (self::DAYS as $day) {
            $d = $saved[$day] ?? [];
            $out[$day] = [
                'open' => (bool) ($d['open'] ?? true),
                'from' => $d['from'] ?? '08:00',
                'to'   => $d['to']   ?? '17:00',
            ];
        }

        return $out;
    }

    public static function formatTime(string $hhmm): string
    {
        return \Carbon\Carbon::createFromFormat('H:i', $hhmm)->format('g:i A');
    }

    public static function notificationPrefs(): array
    {
        $defaults = [
            'rent_due'           => ['email' => true,  'sms' => true,  'push' => false],
            'overdue_notice'     => ['email' => true,  'sms' => false, 'push' => false],
            'announcement'       => ['email' => false, 'sms' => false, 'push' => true],
            'maintenance_status' => ['email' => true,  'sms' => false, 'push' => true],
            'new_request'        => ['email' => true,  'sms' => false, 'push' => true],
            'overdue_digest'     => ['email' => true,  'sms' => false, 'push' => false],
            'new_application'    => ['email' => true,  'sms' => true,  'push' => true],
            'weekly_summary'     => ['email' => true,  'sms' => false, 'push' => false],
        ];
        $saved = self::setting('admin_notifications', []);
        foreach ($defaults as $key => $channels) {
            foreach ($channels as $ch => $on) {
                if (isset($saved[$key][$ch])) {
                    $defaults[$key][$ch] = (bool) $saved[$key][$ch];
                }
            }
        }

        return $defaults;
    }
    public const PAYMENT_METHODS = ['gcash' => 'GCash', 'card' => 'Debit / Credit', 'bank' => 'Bank Transfer'];

public static function paymentMethods(): array
{
    $saved = self::setting('payment_methods', []);
    $saved = is_array($saved) ? $saved : [];
    $out = [];
    foreach (array_keys(self::PAYMENT_METHODS) as $key) {
        $out[$key] = (bool) ($saved[$key] ?? ($key === 'gcash'));
    }
    return $out;
}

public static function enabledMethodNames(): array
{
    $names = [];
    foreach (self::paymentMethods() as $key => $on) {
        if ($on) $names[] = self::PAYMENT_METHODS[$key];
    }
    return $names;
}
public static function profile(): array
{
    $s = self::setting('dorm_profile', []);
    $s = is_array($s) ? $s : [];

    return [
        'name'    => $s['name']    ?? "D'aces Dormitory",
        'phone'   => $s['phone']   ?? '',
        'address' => $s['address'] ?? 'Bulacan, Pinaod',
        'email'   => $s['email']   ?? '',
        'logo'    => $s['logo']    ?? null,
    ];
}

public static function security(): array
{
    $s = self::setting('admin_security', []);
    $s = is_array($s) ? $s : [];

    return [
        'idle_logout'  => (bool) ($s['idle_logout'] ?? false),
        'idle_minutes' => (int)  ($s['idle_minutes'] ?? 30),
    ];
}
public static function roomSettings(): array
{
    $s = self::setting('room_rent', []);
    $s = is_array($s) ? $s : [];

    return [
        'capacity'  => (int)   ($s['capacity']  ?? self::BEDS_PER_ROOM),
        'rate'      => (float) ($s['rate']      ?? self::PRICE_PER_BED),
        'due_day'   => !empty($s['due_day']) ? (int) $s['due_day'] : null,
        'grace'     => (int)   ($s['grace']     ?? 0),
        'late_fee'  => (float) ($s['late_fee']  ?? 10),
        'auto_full' => (bool)  ($s['auto_full'] ?? true),
        'deposit'   => (bool)  ($s['deposit']   ?? true),
        'amenities' => array_values($s['amenities'] ?? ['Ceiling Fan', 'Restroom', 'Terrace', 'Locker', 'Wall Fan']),
    ];
}
}