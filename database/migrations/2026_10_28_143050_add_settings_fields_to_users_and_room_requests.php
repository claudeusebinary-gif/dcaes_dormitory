<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->text('notification_prefs')->nullable();
    });
    Schema::table('room_requests', function (Blueprint $table) {
        $table->date('move_out_date')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', fn (Blueprint $t) => $t->dropColumn('notification_prefs'));
    Schema::table('room_requests', fn (Blueprint $t) => $t->dropColumn('move_out_date'));
}
};
