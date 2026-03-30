<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudent extends Model
{
    protected $fillable = [
        'college_id',
        'program_id',
        'user_id',
        'student_name',
        'student_identifier',
        'email',
        'mobile',
        'department',
        'department_id',
        'status',
        'manager_remarks',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProgramStudent $model) {
            if ($model->department_id) {
                $name = Department::query()->whereKey($model->department_id)->value('name');
                if ($name) {
                    $model->department = $name;
                }
            }
        });
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** College catalog department (optional; denormalized name kept in `department` column). */
    public function collegeDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departmentLabel(): string
    {
        if ($this->department_id) {
            return (string) ($this->collegeDepartment?->name ?? $this->attributes['department'] ?? '');
        }

        return (string) ($this->attributes['department'] ?? '');
    }

    public function isLinkedToUser(): bool
    {
        return $this->user_id !== null;
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ProgramAttendance::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(ProgramFeedback::class);
    }
}
