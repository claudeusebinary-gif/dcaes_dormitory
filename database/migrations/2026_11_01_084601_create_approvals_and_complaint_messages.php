<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::create('approvals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('type', 20);                      // new_tenant | payment | document
        $table->string('title', 150);
        $table->string('status', 20)->default('pending'); // pending | approved | rejected
        $table->timestamps();
        $table->index(['status', 'type']);
    });

    Schema::table('announcements', function (Blueprint $table) {
        $table->string('title', 60)->nullable();
        $table->string('audience', 20)->default('All tenants');
    });

    Schema::create('complaint_messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
        $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
        $table->string('body', 500);
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
        $table->index(['complaint_id', 'read_at']);
    });

    // Backfill: recent bookings and payments show up as pending right away.
    $since = now()->subDays(14);

    foreach (DB::table('users')->where('role', 'tenant')->whereNotNull('room_number')->where('created_at', '>=', $since)->get() as $u) {
        DB::table('approvals')->insert([
            'user_id' => $u->id, 'type' => 'new_tenant', 'status' => 'pending',
            'title' => 'Applied for Room ' . $u->room_number . ($u->bed_number ? ', Bed ' . $u->bed_number : ''),
            'created_at' => $u->created_at, 'updated_at' => now(),
        ]);
    }

    foreach (DB::table('payments')->where('paid_at', '>=', $since)->get() as $p) {
        DB::table('approvals')->insert([
            'user_id' => $p->user_id, 'type' => 'payment', 'status' => 'pending',
            'title' => ucfirst($p->method) . ' payment · ₱' . number_format($p->amount, 2),
            'created_at' => $p->paid_at, 'updated_at' => now(),
        ]);
    }
}

public function down(): void
{
    Schema::dropIfExists('complaint_messages');
    Schema::table('announcements', function (Blueprint $table) {
        $table->dropColumn(['title', 'audience']);
    });
    Schema::dropIfExists('approvals');
}
};
