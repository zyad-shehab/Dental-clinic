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
        Schema::create('laboratory_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_request_id')->constrained('laboratory_requests')->onDelete('cascade');
            $table->string('category'); 
            $table->integer('quantity')->default(1);
            $table->string('tooth_number'); // رقم السن
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_request_items');
    }
};
