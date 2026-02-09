<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_manager_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->enum('manager_type', ['Vendor', 'Independent', 'Internal']);
            $table->unsignedBigInteger('manager_id');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['program_id', 'manager_type', 'manager_id'], 'program_manager_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_manager_credentials');
    }
};
