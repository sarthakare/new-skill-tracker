<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite uses a CHECK on `type` from Schema::enum(); see rebuild_sqlite_vendors_type_for_syllabus migration.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE vendors MODIFY COLUMN type ENUM('Training', 'Certification', 'Logistics', 'Other', 'Syllabus') NOT NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE vendors MODIFY COLUMN type ENUM('Training', 'Certification', 'Logistics', 'Other') NOT NULL");
    }
};
