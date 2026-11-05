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
    Schema::table('marketplace_items', function (Blueprint $table) {
        $table->timestamp('sold_at')->nullable()->after('photos');
    });
}

public function down(): void
{
    Schema::table('marketplace_items', function (Blueprint $table) {
        $table->dropColumn('sold_at');
    });
}
};
