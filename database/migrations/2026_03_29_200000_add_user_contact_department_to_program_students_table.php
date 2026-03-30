<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_students', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('program_id')->constrained()->nullOnDelete();
            $table->string('email')->nullable()->after('student_identifier');
            $table->string('mobile', 32)->nullable()->after('email');
            $table->foreignId('department_id')->nullable()->after('mobile')->constrained()->nullOnDelete();
        });

        if (! Schema::hasTable('departments')) {
            return;
        }

        foreach (DB::table('program_students')->orderBy('id')->get() as $row) {
            $deptName = trim((string) ($row->department ?? ''));
            if ($deptName === '') {
                continue;
            }
            $deptId = DB::table('departments')
                ->where('college_id', $row->college_id)
                ->where('name', $deptName)
                ->value('id');
            if ($deptId) {
                DB::table('program_students')->where('id', $row->id)->update(['department_id' => $deptId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('program_students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['user_id', 'email', 'mobile', 'department_id']);
        });
    }
};
