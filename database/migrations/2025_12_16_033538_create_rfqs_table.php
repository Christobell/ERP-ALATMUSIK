<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('rfqs', function (Blueprint $table) {
        $table->id();
        $table->string('rfq_number')->unique();
        $table->string('title');
        $table->text('description')->nullable();
        $table->date('request_date');
        $table->date('deadline_date');
        $table->unsignedBigInteger('requested_by')->nullable();
        $table->unsignedBigInteger('department_id')->nullable(); // Tanpa foreign key dulu
        $table->decimal('estimated_budget', 15, 2)->default(0);
        $table->enum('status', ['draft', 'pending', 'quotation_received', 'evaluating', 'approved', 'rejected', 'cancelled'])->default('draft');
        $table->json('items')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    public function down()
    {
        Schema::dropIfExists('rfqs');
    }
};