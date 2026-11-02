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
    Schema::table('approvals', function (Blueprint $table) {
        $table->string('applicant_name', 100)->nullable();
        $table->string('room_label', 60)->nullable();
        $table->boolean('is_manual')->default(false);
        $table->json('checks')->nullable();
        $table->string('occupation', 30)->nullable();
        $table->string('civil_status', 20)->nullable();
        $table->string('school', 150)->nullable();
        $table->string('id_type', 50)->nullable();
        $table->string('id_number', 50)->nullable();
        $table->string('length_of_stay', 30)->nullable();
        $table->boolean('agreed')->default(false);
    });

    // manual records have no tenant account
    Schema::table('approvals', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('approvals', function (Blueprint $table) {
        $table->dropColumn([
            'applicant_name', 'room_label', 'is_manual', 'checks', 'occupation',
            'civil_status', 'school', 'id_type', 'id_number', 'length_of_stay', 'agreed',
        ]);
    });
}
};
