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
        Schema::create('laboratory_purchases_items_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_purchase_id')->constrained('laboratory_purchases_models')->onDelete('cascade');
            $table->string('item_name');
            $table->string('number_of_teeth')->nullable(); // رقم الأسنان
            $table->decimal('price', 10, 2)->default(0.00);
            
            $table->integer('quantity')->default(1);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->softDeletes(); // For soft delete functionality
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_purchases_items_models');
    }
};
