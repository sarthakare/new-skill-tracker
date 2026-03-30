<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_program', function (Blueprint $table) {
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->primary(['program_id', 'department_id']);
        });

        if (! Schema::hasTable('departments')) {
            return;
        }

        foreach (DB::table('programs')->orderBy('id')->get() as $p) {
            $raw = trim((string) ($p->department ?? ''));
            if ($raw === '') {
                continue;
            }
            $parts = preg_split('/\s*,\s*/', $raw);
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part === '') {
                    continue;
                }
                $deptId = DB::table('departments')
                    ->where('college_id', $p->college_id)
                    ->where('name', $part)
                    ->value('id');
                if ($deptId) {
                    DB::table('department_program')->insertOrIgnore([
                        'program_id' => $p->id,
                        'department_id' => $deptId,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('department_program');
    }
};
