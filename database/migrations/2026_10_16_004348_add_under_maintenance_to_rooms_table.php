   <?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up(): void
       {
           Schema::table('rooms', function (Blueprint $table) {
               $table->boolean('under_maintenance')->default(false);
           });
       }

       public function down(): void
       {
           Schema::table('rooms', function (Blueprint $table) {
               $table->dropColumn('under_maintenance');
           });
       }
   };