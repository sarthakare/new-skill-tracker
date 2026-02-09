<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Program executor (runs program): manager_type + manager_id (Vendor or Independent).
     * Program manager (oversight): internal_manager_id (Internal Manager for students, attendance, reports).
     */
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('internal_manager_id')->nullable()->after('manager_id')->constrained('internal_managers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['internal_manager_id']);
        });
    }
};
