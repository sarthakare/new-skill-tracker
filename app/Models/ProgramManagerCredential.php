<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramManagerCredential extends Model
{
    protected $fillable = [
        'college_id',
        'program_id',
        'manager_type',
        'manager_id',
        'username',
        'password',
        'last_plain_password',
        'status',
    ];

    protected $hidden = [
        'password',
        'last_plain_password',
    ];

    protected function casts(): array
    {
        return [
            'last_plain_password' => 'encrypted',
        ];
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function vendorManager(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'manager_id');
    }

    public function independentManager(): BelongsTo
    {
        return $this->belongsTo(IndependentTrainer::class, 'manager_id');
    }

    public function internalManager(): BelongsTo
    {
        return $this->belongsTo(InternalManager::class, 'manager_id');
    }

    public function managerLabel(): string
    {
        return match ($this->manager_type) {
            'Vendor' => optional($this->vendorManager)->name ?? 'Manager',
            'Independent' => optional($this->independentManager)->name ?? 'Manager',
            'Internal' => optional($this->internalManager)->name ?? 'Manager',
            default => 'Manager',
        };
    }
}
