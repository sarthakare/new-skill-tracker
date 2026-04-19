<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Recover from a failed run that left the table without recording the migration (MySQL).
        Schema::dropIfExists('syllabus_assignment_submissions');

        Schema::create('syllabus_assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('syllabus_assignment_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // MySQL max identifier length is 64; Laravel's default name exceeds that for this table.
            $table->unique(['user_id', 'syllabus_assignment_id'], 'sas_user_assignment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabus_assignment_submissions');
    }
};
