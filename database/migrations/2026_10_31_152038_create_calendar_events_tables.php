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
    Schema::create('calendar_events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('title');
        $table->string('type');                       // Social | Meeting | Maintenance
        $table->date('event_date');
        $table->time('event_time');
        $table->string('location')->default('Whole Dormitory');
        $table->string('description', 200)->nullable();
        $table->timestamps();
        $table->index('event_date');
    });

    Schema::create('calendar_event_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('calendar_event_id')->constrained('calendar_events')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->timestamps();
        $table->unique(['calendar_event_id', 'user_id']);
    });

    // Sample events, dated relative to today so you can see dots and upcoming events right away.
    $rows = [
        ['Dorm Orientation',        'Meeting',     -4, '17:00', 'Common Area',     'For new tenants.'],
        ['Collecting of Trash',     'Maintenance',  1, '05:00', 'Whole Dormitory', 'Please put trash outside your door.'],
        ['Room 102 Tenant Meeting', 'Meeting',      3, '18:00', 'Room 102',        null],
        ['Movie Night',             'Social',       5, '19:30', 'Common Area',     'Bring your own snacks.'],
        ['All Tenant Meeting',      'Meeting',      7, '20:00', 'Whole Dormitory', 'Monthly dorm updates.'],
        ['Water Tank Maintenance',  'Maintenance', 10, '21:00', 'Whole Dormitory', 'Water may be off for 2 hours.'],
    ];

    foreach ($rows as $r) {
        DB::table('calendar_events')->insert([
            'user_id'     => null,
            'title'       => $r[0],
            'type'        => $r[1],
            'event_date'  => now()->addDays($r[2])->toDateString(),
            'event_time'  => $r[3],
            'location'    => $r[4],
            'description' => $r[5],
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}

public function down(): void
{
    Schema::dropIfExists('calendar_event_user');
    Schema::dropIfExists('calendar_events');
}
};
