<?php

namespace App\Support;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;  


class Billing
{
        public const LATE_FEE_PER_DAY = 10;
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
       $paymentRows = \App\Models\Payment::where('user_id', $user->id)->get()->keyBy('period');
$paidPeriods = $paymentRows->reject(fn ($p) => $p->partial)->keys()->all();   
        $awaiting = \App\Models\Approval::where('user_id', $user->id)
            ->where('type', 'payment')->where('status', 'pending')
            ->whereNotNull('period')->pluck('period')->all();

       
        $all  = [];
$last = $moveIn->copy()->startOfMonth()->addMonths(2);
$window = $today->copy()->startOfMonth()->addMonths(2);
if ($window->gt($last)) $last = $window;     
        for ($m = $moveIn->copy()->startOfMonth(); $m->lte($last); $m->addMonthNoOverflow()) {
            $due = $m->copy()->day(min($dueDay, $m->daysInMonth));
            $period = $m->format('Y-m');
            $part   = $paymentRows->get($period);
$isPart = $part && $part->partial;
$all[] = [
    'label'    => $m->format('F Y'),
    'period'   => $period,
    'due'      => $due,
    'rent'     => $rent,
    'amount'   => $isPart ? max(0, $rent - (float) $part->amount) : $rent,
    'fee_paid' => $part ? (float) $part->fee : 0,
    'awaiting' => in_array($period, $awaiting, true),
    'status'   => in_array($period, $paidPeriods, true)
        ? 'paid'
        : (($due->gt($today) || $isPart) ? 'pending' : 'paid'),
];
        }

       
        $latestPast = null;
        foreach ($all as $i => $inv) {
            if ($inv['due']->lte($today)) {
                $latestPast = $i;
            }
        }
      if ($latestPast !== null && ! in_array($all[$latestPast]['period'], $paidPeriods, true)) {
            $all[$latestPast]['status'] = match ($user->payment_status) {
                'overdue'    => 'overdue',
                'up_to_date' => 'paid',
                default      => 'pending',
            };
        }

             foreach ($all as $i => $inv) {
            $late = ($inv['status'] !== 'paid' && $inv['due']->lt($today))
                ? (int) $inv['due']->diffInDays($today, true)
                : 0;

            $all[$i]['late_days'] = $late;
            $all[$i]['fee']       = max(0, round($late * self::LATE_FEE_PER_DAY - $inv['fee_paid'], 2));
            $all[$i]['half']      = round($rent / 2, 2);
            $all[$i]['can_half']  = $inv['status'] !== 'paid' && abs($inv['amount'] - $rent) < 0.01 && $rent >= 2;
        }
        $overdue = array_filter($all, fn ($i) => $i['status'] === 'overdue');
        $paid    = array_filter($all, fn ($i) => $i['status'] === 'paid' && $i['due']->year === $today->year);
        $pending = array_values(array_filter($all, fn ($i) => $i['status'] === 'pending'));

               $overdueTotal = array_sum(array_map(fn ($i) => $i['amount'] + $i['fee'], $overdue));
        $nextPending  = $pending[0] ?? null;
                $balance      = $overdueTotal + (($nextPending['amount'] ?? 0) + ($nextPending['fee'] ?? 0));
        $firstUnpaid  = $overdue ? reset($overdue) : $nextPending;

        $unpaid     = array_values(array_filter($all, fn ($i) => $i['status'] !== 'paid'));
        $paidList   = array_values(array_filter($all, fn ($i) => $i['status'] === 'paid'));
        $spaceLeft  = max(0, 6 - count($unpaid));
        $display    = array_merge($spaceLeft > 0 ? array_slice($paidList, -$spaceLeft) : [], $unpaid);
        $display    = array_slice($display, 0, 6);
        $history = array_reverse(array_values(array_filter($all, fn ($i) => $i['status'] === 'paid')));

        $format = function (array $i) use ($user, $paymentRows): array {
            $i['due_text'] = $i['due']->format('F j, Y');
            $i['paid_on']  = $i['due']->format('M j, Y');
            $i['ref']      = 'DAC-' . $i['due']->format('Ym') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $p = $paymentRows->get($i['period']);
$i['paid_amount']  = $p ? (float) $p->amount + (float) $p->fee : $i['amount'];
$i['method']       = $p->method ?? 'Recorded by dorm';
$i['paid_at_text'] = $p ? $p->paid_at->format('M j, Y g:i A') : $i['due']->format('M j, Y');
            $i['color']    = match ($i['status']) {
                'paid'    => 'background-color:#90b49f',
                'overdue' => 'background-color:#c9706f',
                default   => '',
            };
            return $i;
        };

      $daysToDue = $firstUnpaid ? (int) $today->diffInDays($firstUnpaid['due'], false) : null;
$nextDueSub = match (true) {
    $daysToDue === null => 'Nothing due',
    $daysToDue < 0      => abs($daysToDue) . ' day' . (abs($daysToDue) === 1 ? '' : 's') . ' overdue',
    $daysToDue === 0    => 'Due today',
    $daysToDue === 1    => 'Due tomorrow',
    default             => 'In ' . $daysToDue . ' days',
};
$ordinal = in_array($dueDay % 100, [11, 12, 13], true)
    ? 'th'
    : ([1 => 'st', 2 => 'nd', 3 => 'rd'][$dueDay % 10] ?? 'th');

return [
    'rent_text'     => '₱' . number_format($rent, 2),
    'due_day_text'  => $dueDay . $ordinal,
    'next_due_text' => $firstUnpaid ? $firstUnpaid['due']->format('M j') : '—',
    'next_due_sub'  => $nextDueSub,
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
  public static function rentFor(User $user): float
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
            'rent_text' => '₱0.00', 'due_day_text' => null, 'next_due_text' => '—', 'next_due_sub' => 'No room booked yet',
            'balance_text' => '₱0', 'balance_sub' => 'No balance due',
            'overdue_text' => '₱0', 'overdue_sub' => 'No overdue invoices',
            'paid_text'    => '₱0', 'paid_sub'    => '0 payments in ' . now()->year,
            'paid_count'   => 0,
        ];
    }
}