<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
   public function up(): void
{
    Schema::create('room_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('type', 20);                      
        $table->string('room_number', 20)->nullable();   
        $table->string('category', 30)->nullable();
        $table->string('urgency', 10)->nullable();
        $table->string('target_room', 20)->nullable();
        $table->unsignedSmallInteger('target_bed')->nullable();
        $table->string('target_deck', 10)->nullable();
        $table->string('reason', 30)->nullable();
        $table->text('details')->nullable();
        $table->string('status', 20)->default('pending'); 
        $table->timestamps();
        $table->index(['type', 'status']);
    });
}

public function down(): void
{
    Schema::dropIfExists('room_requests');
}
};
