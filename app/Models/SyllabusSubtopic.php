<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyllabusSubtopic extends Model
{
    protected $fillable = ['syllabus_topic_id', 'title', 'sort_order', 'is_complete', 'scheduled_date', 'scheduled_time'];

    protected $casts = [
        'is_complete' => 'boolean',
        'scheduled_date' => 'date',
    ];

    public function syllabusTopic(): BelongsTo
    {
        return $this->belongsTo(SyllabusTopic::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SyllabusAssignment::class);
    }
}
