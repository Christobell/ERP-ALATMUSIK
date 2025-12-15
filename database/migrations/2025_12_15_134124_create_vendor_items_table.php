// database/migrations/2025_12_15_create_vendor_items_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained('material')->onDelete('cascade');
            $table->decimal('vendor_price', 15, 2);
            $table->string('unit', 20)->default('pcs');
            $table->integer('lead_time')->nullable()->comment('Hari');
            $table->integer('minimum_order')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Satu vendor tidak boleh punya material yang sama dua kali
            $table->unique(['vendor_id', 'material_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_items');
    }
};