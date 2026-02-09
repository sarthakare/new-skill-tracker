<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_session_id')->constrained('program_sessions')->onDelete('cascade');
            $table->foreignId('program_student_id')->constrained('program_students')->onDelete('cascade');
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->enum('method', ['QR', 'Manual'])->default('Manual');
            $table->timestamps();

            $table->unique(['program_session_id', 'program_student_id'], 'program_session_student_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_attendance');
    }
};
