<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabus_assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('syllabus_assignment_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'syllabus_assignment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabus_assignment_submissions');
    }
};
