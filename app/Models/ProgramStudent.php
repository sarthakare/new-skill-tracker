<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudent extends Model
{
    protected $fillable = [
        'college_id',
        'program_id',
        'student_name',
        'student_identifier',
        'department',
        'status',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ProgramAttendance::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(ProgramFeedback::class);
    }
}
