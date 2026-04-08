<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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

    /**
     * Name for program lists and attendance: live college account when linked, else stored program name.
     */
    public function displayName(): string
    {
        if ($this->user_id) {
            $user = $this->relationLoaded('user') ? $this->user : $this->user()->first();
            if ($user && filled($user->name)) {
                return (string) $user->name;
            }
        }

        return (string) $this->student_name;
    }

    /**
     * Roll / ID for attendance: user roll_number when linked, else manual student_identifier.
     */
    public function displayRollNumber(): ?string
    {
        if ($this->user_id) {
            $user = $this->relationLoaded('user') ? $this->user : $this->user()->first();
            if ($user && filled($user->roll_number)) {
                return (string) $user->roll_number;
            }
        }

        $id = $this->student_identifier;

        return filled($id) ? (string) $id : null;
    }

    /**
     * Sort by roll number, then name (missing rolls last).
     *
     * @param  Collection<int, ProgramStudent>  $students
     * @return Collection<int, ProgramStudent>
     */
    public static function sortByRollThenName(Collection $students): Collection
    {
        return $students->sort(function (ProgramStudent $a, ProgramStudent $b) {
            $ra = $a->displayRollNumber();
            $rb = $b->displayRollNumber();
            $emptyA = $ra === null || $ra === '';
            $emptyB = $rb === null || $rb === '';
            if ($emptyA !== $emptyB) {
                return $emptyA ? 1 : -1;
            }
            if (! $emptyA) {
                $c = strnatcasecmp((string) $ra, (string) $rb);
                if ($c !== 0) {
                    return $c;
                }
            }

            return strnatcasecmp($a->displayName(), $b->displayName());
        })->values();
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
