<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IndependentTrainer extends Model
{
    protected $fillable = [
        'college_id',
        'name',
        'email',
        'phone',
        'expertise',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'manager_id')
            ->where('manager_type', 'Independent');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_independent_trainers')
            ->withTimestamps();
    }
}
