<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramSession extends Model
{
    protected $fillable = [
        'college_id',
        'program_id',
        'title',
        'session_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ProgramAttendance::class);
    }

    public function taughtSyllabus(): BelongsToMany
    {
        return $this->belongsToMany(SyllabusTopic::class, 'program_session_syllabus', 'program_session_id', 'syllabus_topic_id')
            ->withTimestamps();
    }
}
