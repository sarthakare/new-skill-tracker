<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $fillable = [
        'college_id',
        'name',
        'description',
        'type',
        'start_date',
        'end_date',
        'target_audience',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function eventUsers(): HasMany
    {
        return $this->hasMany(EventUser::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function modules(): HasMany
    {
        return $this->hasMany(EventModule::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'event_vendors')
            ->withTimestamps();
    }

    public function independentTrainers(): BelongsToMany
    {
        return $this->belongsToMany(IndependentTrainer::class, 'event_independent_trainers')
            ->withTimestamps();
    }

    public function vendorCredentials(): HasMany
    {
        return $this->hasMany(VendorEventCredential::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
