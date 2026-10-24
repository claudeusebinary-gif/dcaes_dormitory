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
        Schema::create('announcements', function (Blueprint $table) {
    $table->id();
    $table->string('message', 150);
    $table->boolean('is_active')->default(true);
    $table->timestamp('starts_at')->nullable();   // show from (empty = right away)
    $table->timestamp('ends_at')->nullable();     // hide after (empty = never)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
