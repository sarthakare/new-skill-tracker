<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramFeedback extends Model
{
    protected $table = 'program_feedback';

    protected $fillable = [
        'program_id',
        'program_student_id',
        'trainer_rating',
        'content_rating',
        'overall_rating',
        'comments',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(ProgramStudent::class, 'program_student_id');
    }
}
