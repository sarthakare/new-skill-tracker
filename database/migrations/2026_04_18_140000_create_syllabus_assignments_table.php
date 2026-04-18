<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabus_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_subtopic_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('difficulty', 32);
            $table->text('starter_code')->nullable();
            $table->text('test_cases');
            $table->text('expected_output');
            $table->unsignedInteger('time_limit');
            $table->json('languages_supported');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabus_assignments');
    }
};
