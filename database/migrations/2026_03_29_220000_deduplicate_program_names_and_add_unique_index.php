<?php

use App\Models\Program;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Resolve exact duplicate (event_id, name) rows so a unique index can be added.
        $seen = [];
        foreach (Program::orderBy('id')->cursor() as $program) {
            $key = $program->event_id.'|'.$program->name;
            if (isset($seen[$key])) {
                Program::where('id', $program->id)->update([
                    'name' => $program->name.' ('.$program->id.')',
                ]);
            } else {
                $seen[$key] = true;
            }
        }

        Schema::table('programs', function (Blueprint $table) {
            $table->unique(['event_id', 'name'], 'programs_event_id_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropUnique('programs_event_id_name_unique');
        });
    }
};
