<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('syllabus_topics', function (Blueprint $table) {
            $table->date('scheduled_date')->nullable()->after('is_complete');
            $table->time('scheduled_time')->nullable()->after('scheduled_date');
        });
    }

    public function down(): void
    {
        Schema::table('syllabus_topics', function (Blueprint $table) {
            $table->dropColumn(['scheduled_date', 'scheduled_time']);
        });
    }
};
