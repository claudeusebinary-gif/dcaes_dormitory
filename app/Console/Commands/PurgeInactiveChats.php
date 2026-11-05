<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeInactiveChats extends Command
{
    protected $signature = 'chats:purge-inactive
                            {--days=2 : Days of inactivity before messages are deleted}';

    protected $description = 'Delete chatbox messages from conversations inactive for N days (default 2)';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $total = 0;

        // ── Marketplace messages ──────────────────────────────
        // A "thread" is a unique (item_id, sender_id, recipient_id) pair
        // (both directions). Delete every message in threads whose newest
        // message is older than the cutoff.
        $staleMarketplace = DB::table('marketplace_messages')
            ->select(
                'item_id',
                DB::raw('MIN(sender_id, recipient_id) AS user_a'),
                DB::raw('MAX(sender_id, recipient_id) AS user_b'),
            )
            ->groupBy('item_id', DB::raw('MIN(sender_id, recipient_id)'), DB::raw('MAX(sender_id, recipient_id)'))
            ->havingRaw('MAX(created_at) < ?', [$cutoff])
            ->get();

        foreach ($staleMarketplace as $t) {
            $deleted = DB::table('marketplace_messages')
                ->where('item_id', $t->item_id)
                ->where(function ($q) use ($t) {
                    $q->where(function ($q2) use ($t) {
                        $q2->where('sender_id', $t->user_a)->where('recipient_id', $t->user_b);
                    })->orWhere(function ($q2) use ($t) {
                        $q2->where('sender_id', $t->user_b)->where('recipient_id', $t->user_a);
                    });
                })
                ->delete();
            $total += $deleted;
        }

        // ── Complaint messages ────────────────────────────────
        // A "thread" is all messages under one complaint_id.
        $staleComplaints = DB::table('complaint_messages')
            ->select('complaint_id')
            ->groupBy('complaint_id')
            ->havingRaw('MAX(created_at) < ?', [$cutoff])
            ->pluck('complaint_id');

        if ($staleComplaints->isNotEmpty()) {
            $deleted = DB::table('complaint_messages')
                ->whereIn('complaint_id', $staleComplaints)
                ->delete();
            $total += $deleted;
        }

        // ── Direct messages ──────────────────────────────────
        // A "thread" is all messages for a given tenant_id (admin ↔ tenant).
        $staleDMs = DB::table('direct_messages')
            ->select('tenant_id')
            ->groupBy('tenant_id')
            ->havingRaw('MAX(created_at) < ?', [$cutoff])
            ->pluck('tenant_id');

        if ($staleDMs->isNotEmpty()) {
            $deleted = DB::table('direct_messages')
                ->whereIn('tenant_id', $staleDMs)
                ->delete();
            $total += $deleted;
        }

        $this->info("Purged {$total} message(s) from conversations inactive for {$days}+ day(s).");

        return self::SUCCESS;
    }
}
