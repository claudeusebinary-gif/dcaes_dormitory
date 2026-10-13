<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('move_in_date')->nullable();
            $table->decimal('monthly_rent', 10, 2)->nullable();
            // up_to_date | due | overdue
            $table->string('payment_status', 20)->default('due');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['move_in_date', 'monthly_rent', 'payment_status']);
        });
    }
};