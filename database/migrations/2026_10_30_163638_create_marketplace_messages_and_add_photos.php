<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
  public function up(): void
{
    Schema::create('marketplace_messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained('marketplace_items')->cascadeOnDelete();
        $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
        $table->string('body', 500);
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
        $table->index(['item_id', 'sender_id', 'recipient_id']);
        $table->index(['recipient_id', 'read_at']);
    });

    Schema::table('marketplace_items', function (Blueprint $table) {
        $table->text('photos')->nullable();
    });
}

public function down(): void
{
    Schema::table('marketplace_items', fn (Blueprint $t) => $t->dropColumn('photos'));
    Schema::dropIfExists('marketplace_messages');
}
};
