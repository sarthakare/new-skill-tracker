<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'college_id',
        'name',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'STUDENT');
    }

    public function internalManagers(): HasMany
    {
        return $this->hasMany(InternalManager::class);
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'department_program');
    }
}
