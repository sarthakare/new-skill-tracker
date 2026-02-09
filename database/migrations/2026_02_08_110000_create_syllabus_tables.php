<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabus_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });

        Schema::create('syllabus_subtopics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_topic_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabus_subtopics');
        Schema::dropIfExists('syllabus_topics');
    }
};
