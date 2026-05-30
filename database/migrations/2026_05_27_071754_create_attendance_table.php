<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            
            // 1. Gagawa muna ng mga columns sa itaas
            $table->string('student_name');
            $table->string('student_id_number'); // Nilikha na dito
            
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late']);
            $table->timestamps();
            
            // 2. SA PINAKAHULI: Dito pa lang natin pwedeng gawing unique ang nakalistang columns sa itaas
            $table->unique(['school_class_id', 'student_id_number', 'attendance_date'], 'unique_attendance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};