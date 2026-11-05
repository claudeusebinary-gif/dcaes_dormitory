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
    Schema::create('admin_activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('admin_name');
        $table->string('section', 40);
        $table->string('action');
        $table->json('changes')->nullable();
        $table->string('ip', 45)->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('admin_activity_logs');
}
};
