<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['college_id', 'name']);
        });

        foreach (DB::table('branches')->get() as $row) {
            DB::table('departments')->insert((array) $row);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('college_id');
        });

        DB::statement('UPDATE users SET department_id = branch_id WHERE branch_id IS NOT NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('college_id');
        });

        DB::statement('UPDATE internal_managers SET department_id = branch_id WHERE branch_id IS NOT NULL');

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
        });

        Schema::dropIfExists('branches');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['college_id', 'name']);
        });

        foreach (DB::table('departments')->get() as $row) {
            DB::table('branches')->insert((array) $row);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('college_id');
        });

        DB::statement('UPDATE users SET branch_id = department_id WHERE department_id IS NOT NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('college_id');
        });

        DB::statement('UPDATE internal_managers SET branch_id = department_id WHERE department_id IS NOT NULL');

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->dropColumn('department_id');
        });

        Schema::table('internal_managers', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });

        Schema::dropIfExists('departments');
    }
};
