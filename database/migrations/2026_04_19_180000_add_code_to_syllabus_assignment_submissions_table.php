<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('syllabus_assignment_submissions', function (Blueprint $table) {
            $table->longText('source_code')->nullable()->after('syllabus_assignment_id');
            $table->unsignedInteger('judge0_language_id')->nullable()->after('source_code');
        });
    }

    public function down(): void
    {
        Schema::table('syllabus_assignment_submissions', function (Blueprint $table) {
            $table->dropColumn(['source_code', 'judge0_language_id']);
        });
    }
};
