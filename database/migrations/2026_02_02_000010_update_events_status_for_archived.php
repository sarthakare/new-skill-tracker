<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('events_new', function (Blueprint $table) {
                $table->id();
                $table->foreignId('college_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->enum('type', ['Training', 'Hackathon', 'Seminar']);
                $table->date('start_date');
                $table->date('end_date');
                $table->enum('status', ['Draft', 'Active', 'Completed', 'Archived'])->default('Draft');
                $table->timestamps();
            });

            DB::statement('INSERT INTO events_new (id, college_id, name, type, start_date, end_date, status, created_at, updated_at)
                SELECT id, college_id, name, type, start_date, end_date, status, created_at, updated_at FROM events');
            Schema::drop('events');
            Schema::rename('events_new', 'events');
            DB::statement('PRAGMA foreign_keys=ON');

            return;
        }

        DB::statement("ALTER TABLE events MODIFY status ENUM('Draft','Active','Completed','Archived') NOT NULL DEFAULT 'Draft'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('events_new', function (Blueprint $table) {
                $table->id();
                $table->foreignId('college_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->enum('type', ['Training', 'Hackathon', 'Seminar']);
                $table->date('start_date');
                $table->date('end_date');
                $table->enum('status', ['Draft', 'Active', 'Completed'])->default('Draft');
                $table->timestamps();
            });

            DB::statement('INSERT INTO events_new (id, college_id, name, type, start_date, end_date, status, created_at, updated_at)
                SELECT id, college_id, name, type, start_date, end_date, status, created_at, updated_at FROM events');
            Schema::drop('events');
            Schema::rename('events_new', 'events');
            DB::statement('PRAGMA foreign_keys=ON');

            return;
        }

        DB::statement("ALTER TABLE events MODIFY status ENUM('Draft','Active','Completed') NOT NULL DEFAULT 'Draft'");
    }
};
