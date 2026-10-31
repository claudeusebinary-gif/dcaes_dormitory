    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
       
       public function up(): void
{
    Schema::create('marketplace_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
        $table->string('category');
        $table->string('title');
        $table->unsignedInteger('price')->default(0);
        $table->string('description', 200)->nullable();
        $table->timestamps();
    });
}
        
        public function down(): void
        {
            Schema::dropIfExists('marketplace_items');
        }
    };
