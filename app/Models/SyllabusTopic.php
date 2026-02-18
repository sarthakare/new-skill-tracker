<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyllabusTopic extends Model
{
    protected $fillable = ['program_id', 'title', 'sort_order', 'is_complete', 'scheduled_date', 'scheduled_time'];

    protected $casts = [
        'is_complete' => 'boolean',
        'scheduled_date' => 'date',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function subtopics(): HasMany
    {
        return $this->hasMany(SyllabusSubtopic::class)->orderBy('sort_order');
    }

    public function programSessions(): BelongsToMany
    {
        return $this->belongsToMany(ProgramSession::class, 'program_session_syllabus', 'syllabus_topic_id', 'program_session_id')
            ->withTimestamps();
    }
}
