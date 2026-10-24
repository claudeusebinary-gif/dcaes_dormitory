<?php

namespace App\Support;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;  


class Billing
{
    public static function money(float $amount): string
    {
        return '₱' . number_format($amount, fmod($amount, 1) == 0 ? 0 : 2);
    }

    public static function forUser(User $user): array
    {
        $rent = self::rentFor($user);

        if (! $user->room_number || $rent <= 0) {
            return self::blank($user->room_number);
        }

        $today  = now()->startOfDay();
        $moveIn = Carbon::parse($user->move_in_date ?? $user->created_at)->startOfDay();
        $dueDay = $moveIn->day;

        // 1) one bill per month, from the move-in month to 4 months ahead
        $all  = [];
        $last = $today->copy()->startOfMonth()->addMonths(4);
        for ($m = $moveIn->copy()->startOfMonth(); $m->lte($last); $m->addMonthNoOverflow()) {
            $due = $m->copy()->day(min($dueDay, $m->daysInMonth));
            $all[] = [
                'label'  => $m->format('F Y'),
                'due'    => $due,
                'amount' => $rent,
                'status' => $due->gt($today) ? 'pending' : 'paid',
            ];
        }

        // 2) the latest bill whose due date has passed takes the tenant's real status
        $latestPast = null;
        foreach ($all as $i => $inv) {
            if ($inv['due']->lte($today)) {
                $latestPast = $i;
            }
        }
        if ($latestPast !== null) {
            $all[$latestPast]['status'] = match ($user->payment_status) {
                'overdue'    => 'overdue',
                'up_to_date' => 'paid',
                default      => 'pending',
            };
        }

        // 3) the numbers for the cards
        $overdue = array_filter($all, fn ($i) => $i['status'] === 'overdue');
        $paid    = array_filter($all, fn ($i) => $i['status'] === 'paid' && $i['due']->year === $today->year);
        $pending = array_values(array_filter($all, fn ($i) => $i['status'] === 'pending'));

        $overdueTotal = array_sum(array_column($overdue, 'amount'));
        $nextPending  = $pending[0] ?? null;
        $balance      = $overdueTotal + ($nextPending['amount'] ?? 0);
        $firstUnpaid  = $overdue ? reset($overdue) : $nextPending;

        // 4) rows for the list (last 3 months + everything unpaid) and the full paid history
        // list shows 6 invoices max: every unpaid one, topped up with the latest paid ones
        $unpaid     = array_values(array_filter($all, fn ($i) => $i['status'] !== 'paid'));
        $paidList   = array_values(array_filter($all, fn ($i) => $i['status'] === 'paid'));
        $spaceLeft  = max(0, 6 - count($unpaid));
        $display    = array_merge($spaceLeft > 0 ? array_slice($paidList, -$spaceLeft) : [], $unpaid);
        $display    = array_slice($display, 0, 6);
        $history = array_reverse(array_values(array_filter($all, fn ($i) => $i['status'] === 'paid')));

        $format = function (array $i) use ($user): array {
            $i['due_text'] = $i['due']->format('F j, Y');
            $i['paid_on']  = $i['due']->format('M j, Y');
            $i['ref']      = 'DAC-' . $i['due']->format('Ym') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $i['color']    = match ($i['status']) {
                'paid'    => 'background-color:#90b49f',
                'overdue' => 'background-color:#c9706f',
                default   => '',
            };
            return $i;
        };

        return [
            'room'          => $user->room_number,
            'rent'          => $rent,
            'invoices'      => array_map($format, $display),
            'history'       => array_map($format, $history),
            'balance_text'  => self::money($balance),
            'balance_sub'   => $firstUnpaid ? 'Due ' . $firstUnpaid['due']->format('M j, Y') : 'No balance due',
            'overdue_text'  => self::money($overdueTotal),
            'overdue_sub'   => count($overdue) ? count($overdue) . ' invoice' . (count($overdue) > 1 ? 's' : '') . ' past due' : 'No overdue invoices',
            'paid_text'     => self::money((float) array_sum(array_column($paid, 'amount'))),
            'paid_sub'      => count($paid) . ' payment' . (count($paid) === 1 ? '' : 's') . ' in ' . $today->year,
            'paid_count'    => count($paid),
        ];
    }
 private static function rentFor(User $user): float
    {
        if (! $user->room_number) {
            return 0;
        }

        $price   = (float) Room::where('room_number', $user->room_number)->value('price_per_bed');
        $custom  = $user->monthly_rent !== null ? (float) $user->monthly_rent : null;
        $sharers = max(1, User::where('role', 'tenant')->where('room_number', $user->room_number)->count());

        if ($custom !== null && abs($custom - $price) > 0.01) {
            return $custom;
        }

        return round($price / $sharers, 2);
    }
    private static function blank(?string $room): array
    {
        return [
            'room' => $room, 'rent' => 0, 'invoices' => [], 'history' => [],
            'balance_text' => '₱0', 'balance_sub' => 'No balance due',
            'overdue_text' => '₱0', 'overdue_sub' => 'No overdue invoices',
            'paid_text'    => '₱0', 'paid_sub'    => '0 payments in ' . now()->year,
            'paid_count'   => 0,
        ];
    }
}