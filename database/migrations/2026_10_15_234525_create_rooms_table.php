<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number', 20)->unique();
            $table->string('sharing_type', 30)->default('Bed Sharing');
            $table->string('bed_type', 30)->default('Double Deck');
            $table->unsignedTinyInteger('total_beds')->default(6);
            $table->decimal('price_per_bed', 10, 2)->default(2000);
            $table->string('photo')->nullable();   // e.g. images/rooms/room-100.jpg
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};