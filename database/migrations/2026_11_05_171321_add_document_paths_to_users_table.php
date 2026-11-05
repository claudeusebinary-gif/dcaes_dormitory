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
    Schema::table('users', function (Blueprint $table) {
        $table->string('valid_id_path')->nullable();
        $table->string('lease_path')->nullable();
        $table->string('barangay_clearance_path')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['valid_id_path', 'lease_path', 'barangay_clearance_path']);
    });
}
};
