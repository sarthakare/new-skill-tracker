<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramAttendance extends Model
{
    protected $table = 'program_attendance';

    protected $fillable = [
        'program_session_id',
        'program_student_id',
        'status',
        'method',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ProgramSession::class, 'program_session_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(ProgramStudent::class, 'program_student_id');
    }
}
