<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_student_id')->constrained('program_students')->onDelete('cascade');
            $table->unsignedTinyInteger('trainer_rating');
            $table->unsignedTinyInteger('content_rating');
            $table->unsignedTinyInteger('overall_rating');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_feedback');
    }
};
