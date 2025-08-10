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
        Schema::create('clinic_sessions', function (Blueprint $table) {
            $table->id();
           

            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctor')->onDelete('cascade');

            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();

            $table->text('drug')->nullable();
            $table->double('required_amount')->nullable();
            $table->double('cash_payment')->default(0);
            $table->double('card_payment')->default(0);
            $table->double('total_amount')->default(0);
            $table->string('name_of_bank_account')->nullable();
            $table->double('remaining_amount')->default(0);

            $table->string('xray_image')->nullable(); // we'll handle image upload later
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_sessions');
    }
};
