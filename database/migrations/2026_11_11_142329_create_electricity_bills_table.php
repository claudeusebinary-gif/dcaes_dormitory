<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('electricity_bills', function (Blueprint $table) {
            $table->id();
            $table->string('room_number', 20);
            $table->string('period', 7);            // e.g. 2026-11
            $table->decimal('amount', 10, 2);
            $table->string('photo_path')->nullable();
            $table->timestamps();
            $table->unique(['room_number', 'period']);
        });

        Schema::table('approvals', function (Blueprint $table) {
            $table->string('proof_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('electricity_bills');
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn('proof_path');
        });
    }
};