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
    Schema::create('dorm_settings', function (Blueprint $table) {
        $table->string('key')->primary();
        $table->longText('value')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}

public function down(): void
{
    Schema::dropIfExists('dorm_settings');
}
};
