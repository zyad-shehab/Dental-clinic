<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   

    /**
     * Run the migrations.
     */
    
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('session_service_tooth', function (Blueprint $table) {
        $table->id();
      
        $table->foreignId('clinic_session_id')->constrained('clinic_sessions')->onDelete('cascade');
        $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
        
        $table->string('tooth_number'); // مثل: 18، 24، 36... إلخ

        $table->timestamps();
        $table->softDeletes(); // لإدارة الحذف الناعم
    });

       
    }

  

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_service_tooth');
    }
};


    /**
     * Reverse the migrations.
     */
   
