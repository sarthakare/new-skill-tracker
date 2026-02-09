<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyllabusSubtopic extends Model
{
    protected $fillable = ['syllabus_topic_id', 'title', 'sort_order', 'is_complete'];

    protected $casts = [
        'is_complete' => 'boolean',
    ];

    public function syllabusTopic(): BelongsTo
    {
        return $this->belongsTo(SyllabusTopic::class);
    }
}
