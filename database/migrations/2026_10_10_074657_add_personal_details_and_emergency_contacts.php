<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sex', 10)->nullable();      // male | female
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
        });

        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('relationship', 50);
            $table->string('phone', 20);
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sex', 'birthday', 'address']);
        });
    }
};