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
        Schema::create('warehouse_payments_models', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->foreignId('storehouses_id')->constrained('storehouses')->onDelete('cascade');
            $table->decimal('paid_cash', 10, 2)->default(0.00);
            $table->decimal('paid_card', 10, 2)->default(0.00);
            $table->string('name_of_bank_account')->nullable();
            $table->decimal('total', 10, 2)->default(0.00); // اسم الحساب البنكي
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_payments_models');
    }
};
