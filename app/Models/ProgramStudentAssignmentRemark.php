<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramStudentAssignmentRemark extends Model
{
    protected $fillable = [
        'program_student_id',
        'syllabus_assignment_id',
        'remarks',
    ];

    public function programStudent(): BelongsTo
    {
        return $this->belongsTo(ProgramStudent::class);
    }

    public function syllabusAssignment(): BelongsTo
    {
        return $this->belongsTo(SyllabusAssignment::class);
    }
}
