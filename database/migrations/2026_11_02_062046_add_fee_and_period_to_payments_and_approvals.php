<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('fee', 10, 2)->default(0);
        });

        Schema::table('approvals', function (Blueprint $table) {
            $table->string('period', 7)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->boolean('partial')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('fee');
        });

        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn(['period', 'amount', 'fee', 'partial']);
        });
    }
};