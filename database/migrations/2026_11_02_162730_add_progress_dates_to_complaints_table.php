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
    Schema::table('complaints', function (Blueprint $table) {
        $table->timestamp('in_progress_at')->nullable();
        $table->timestamp('resolved_at')->nullable();
    });
}
public function down(): void
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->dropColumn(['in_progress_at', 'resolved_at']);
    });
}
};
