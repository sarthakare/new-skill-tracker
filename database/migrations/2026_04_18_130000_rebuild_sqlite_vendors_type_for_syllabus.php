<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        Schema::disableForeignKeyConstraints();

        Schema::rename('vendors', 'vendors_old');

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['Training', 'Certification', 'Logistics', 'Other', 'Syllabus']);
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        DB::statement('
            INSERT INTO vendors (id, college_id, name, type, contact_email, contact_phone, address, created_at, updated_at)
            SELECT id, college_id, name, type, contact_email, contact_phone, address, created_at, updated_at
            FROM vendors_old
        ');

        Schema::drop('vendors_old');

        $maxId = (int) DB::table('vendors')->max('id');
        if ($maxId > 0) {
            DB::delete("DELETE FROM sqlite_sequence WHERE name = 'vendors'");
            DB::insert('INSERT INTO sqlite_sequence (name, seq) VALUES (?, ?)', ['vendors', $maxId]);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // SQLite CHECK cannot be narrowed safely if any row uses Syllabus (FK children would orphan).
    }
};
