<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE vendors MODIFY COLUMN type ENUM('Training', 'Certification', 'Logistics', 'Other') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE vendors MODIFY COLUMN type ENUM('Training', 'Certification', 'Logistics') NOT NULL");
    }
};
