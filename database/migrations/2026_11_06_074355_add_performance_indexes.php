<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->index(['role', 'room_number'], 'users_role_room_idx'));
        Schema::table('approvals', fn (Blueprint $t) => $t->index(['user_id', 'type', 'status'], 'approvals_user_type_status_idx'));
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropIndex('users_role_room_idx'));
        Schema::table('approvals', fn (Blueprint $t) => $t->dropIndex('approvals_user_type_status_idx'));
    }
};