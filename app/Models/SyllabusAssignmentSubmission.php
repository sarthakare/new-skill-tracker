<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyllabusAssignmentSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'syllabus_assignment_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function syllabusAssignment(): BelongsTo
    {
        return $this->belongsTo(SyllabusAssignment::class);
    }
}
