<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE programs MODIFY COLUMN type ENUM('Training', 'Hackathon', 'Seminar', 'Other') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE programs MODIFY COLUMN type ENUM('Training', 'Hackathon', 'Seminar') NULL");
    }
};
