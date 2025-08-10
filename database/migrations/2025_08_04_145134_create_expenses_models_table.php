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
        Schema::create('expenses_models', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('pay_to');
             $table->decimal('paid_cash', 10, 2)->default(0.00);
            $table->decimal('paid_card', 10, 2)->default(0.00);
            $table->string('name_of_bank_account')->nullable();
            $table->decimal('total', 10, 2)->default(0.00);
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_models');
    }
};
