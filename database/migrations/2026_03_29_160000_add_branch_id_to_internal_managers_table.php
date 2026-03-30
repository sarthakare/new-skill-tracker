<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('internal_managers', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('college_id')->constrained()->nullOnDelete();
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_managers', function (Blueprint $table) {
            $table->string('department')->after('college_id');
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
