<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->string('room_number', 10)->nullable();
            $table->unsignedTinyInteger('bed_number')->nullable();
            $table->string('bed_deck', 10)->nullable();
            $table->date('move_in_date')->nullable();
            $table->decimal('monthly_rent', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn(['room_number', 'bed_number', 'bed_deck', 'move_in_date', 'monthly_rent']);
        });
    }
};