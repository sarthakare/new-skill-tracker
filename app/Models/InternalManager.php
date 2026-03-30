<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalManager extends Model
{
    protected $fillable = [
        'college_id',
        'department_id',
        'name',
        'email',
        'phone',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** Legacy: programs where this internal was stored in manager_id. */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'manager_id')
            ->where('manager_type', 'Internal');
    }

    /** Programs where this internal manager is assigned for oversight (students, attendance, reports). */
    public function programsManaged(): HasMany
    {
        return $this->hasMany(Program::class, 'internal_manager_id');
    }
}
