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
    Schema::create('bom', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')
              ->constrained('product')
              ->cascadeOnDelete();

        $table->decimal('total_price', 15, 2)->default(0);
        $table->enum('status', ['pending', 'in_progress', 'completed'])
              ->default('pending');

        $table->timestamps();
    });

    Schema::create('bom_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bom_id')
              ->constrained('bom')
              ->cascadeOnDelete();

        $table->foreignId('material_id')
              ->constrained('material')
              ->cascadeOnDelete();

        $table->decimal('quantity', 12, 2);
        $table->string('unit', 20);

        $table->decimal('unit_price', 15, 2);
        $table->decimal('subtotal_price', 15, 2);

        $table->timestamps();

        // Material tidak boleh dobel dalam satu BOM
        $table->unique(['bom_id', 'material_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom');
    }
};
