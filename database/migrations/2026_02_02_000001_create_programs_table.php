<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('department');
            $table->unsignedInteger('duration_days');
            $table->enum('mode', ['On-Campus', 'Online', 'Hybrid'])->default('On-Campus');
            $table->enum('status', [
                'Draft',
                'Manager_Assigned',
                'Registration_Open',
                'In_Progress',
                'Completed',
                'Approved',
            ])->default('Draft');
            $table->enum('manager_type', ['Vendor', 'Independent', 'Internal'])->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
