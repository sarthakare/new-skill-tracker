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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('COLLEGE_ADMIN')->after('email');
            }
            if (! Schema::hasColumn('users', 'college_id')) {
                $table->foreignId('college_id')->nullable()->after('role')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'college_id')) {
                $table->dropForeign(['college_id']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = collect(['college_id', 'role'])
                ->filter(fn (string $column): bool => Schema::hasColumn('users', $column))
                ->values()
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
