<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventIndependentTrainer extends Model
{
    protected $fillable = [
        'event_id',
        'independent_trainer_id',
        'college_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function independentTrainer(): BelongsTo
    {
        return $this->belongsTo(IndependentTrainer::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }
}
