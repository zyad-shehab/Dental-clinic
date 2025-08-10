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
        Schema::create('warehouse_purchases_items_models', function (Blueprint $table) {
            $table->id();
             $table->foreignId('warehouse_purchases_id')->constrained('warehouse_purchases_models')->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->date('end_date')->nullable(); // تاريخ انتهاء الصلاحية
            $table->enum('status', ['Available', 'finished'])->default('Available');
            $table->softDeletes(); // For soft delete functionality
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_purchases_items_models');
    }
};
