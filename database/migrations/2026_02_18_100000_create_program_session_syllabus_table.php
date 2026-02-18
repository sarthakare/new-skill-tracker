<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_session_syllabus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_session_id')->constrained('program_sessions')->onDelete('cascade');
            $table->foreignId('syllabus_topic_id')->constrained('syllabus_topics')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['program_session_id', 'syllabus_topic_id'], 'session_topic_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_session_syllabus');
    }
};
