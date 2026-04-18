<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('syllabus_assignments', function (Blueprint $table) {
            $table->text('test_cases')->nullable()->change();
            $table->text('expected_output')->nullable()->change();
            $table->unsignedInteger('time_limit')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('syllabus_assignments', function (Blueprint $table) {
            $table->text('test_cases')->nullable(false)->change();
            $table->text('expected_output')->nullable(false)->change();
            $table->unsignedInteger('time_limit')->nullable(false)->change();
        });
    }
};
