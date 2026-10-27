<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('period', 7);               // e.g. 2026-10
            $table->decimal('amount', 10, 2);
            $table->string('method', 30);
            $table->string('reference', 40)->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->unique(['user_id', 'period']);     // a month can only be paid once
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};