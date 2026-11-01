<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('direct_messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
        $table->string('body', 500);
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
        $table->index(['tenant_id', 'read_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('direct_messages');
}
};
