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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->char('phone',10)->unique()->nullable();
            $table->string('address');
            $table->date('date_of_birth');
            $table->string('chronic_diseases')->nullable(); //أمراض مزمنة
            $table->string('allergies')->nullable(); //حساسية
            $table->string('clinic_source')->nullable(); //مصدر العيادة
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient');
    }
};
