<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Program extends Model
{
    protected $fillable = [
        'college_id',
        'event_id',
        'name',
        'type',
        'department',
        'duration_days',
        'mode',
        'status',
        'manager_type',
        'manager_id',
        'internal_manager_id',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    /** College-defined departments this program applies to (many-to-many). */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_program');
    }

    /**
     * Department IDs linked on the pivot. Empty means no rows (legacy: do not filter by department).
     *
     * @return Collection<int, int>
     */
    public function scopedDepartmentIds(): Collection
    {
        if ($this->relationLoaded('departments')) {
            return $this->departments->pluck('id')->unique()->values();
        }

        return $this->departments()->pluck('id')->unique()->values();
    }

    /**
     * Program students visible for this program: enrolled rows whose department matches linked departments.
     * When the program has no linked departments, returns all enrolled students (legacy).
     */
    public function programStudentsQuery(): Builder
    {
        $query = ProgramStudent::query()->where('program_id', $this->id);
        $deptIds = $this->scopedDepartmentIds();
        if ($deptIds->isNotEmpty()) {
            $query->whereIn('department_id', $deptIds);
        }

        return $query;
    }

    /**
     * Comma-separated department names from the catalog, or legacy free-text from programs.department.
     */
    public function departmentsLabel(): string
    {
        $names = $this->departments()->orderBy('name')->pluck('name');
        if ($names->isNotEmpty()) {
            return $names->implode(', ');
        }

        return (string) ($this->attributes['department'] ?? '');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(ProgramStudent::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ProgramSession::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(ProgramFeedback::class);
    }

    public function completionRequests(): HasMany
    {
        return $this->hasMany(ProgramCompletionRequest::class);
    }

    public function syllabusTopics(): HasMany
    {
        return $this->hasMany(SyllabusTopic::class)->orderBy('sort_order');
    }

    public function vendorManager(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'manager_id');
    }

    public function independentManager(): BelongsTo
    {
        return $this->belongsTo(IndependentTrainer::class, 'manager_id');
    }

    /** Legacy: when manager_type was Internal and manager_id pointed to internal manager. */
    public function internalManager(): BelongsTo
    {
        return $this->belongsTo(InternalManager::class, 'manager_id');
    }

    /** Program manager (oversight): manages students, attendance, points, reports. */
    public function oversightManager(): BelongsTo
    {
        return $this->belongsTo(InternalManager::class, 'internal_manager_id');
    }

    /** Who runs the program: Vendor or Independent Trainer. */
    public function executorLabel(): string
    {
        return match ($this->manager_type) {
            'Vendor' => optional($this->vendorManager)->name ?? 'Unassigned',
            'Independent' => optional($this->independentManager)->name ?? 'Unassigned',
            default => 'Unassigned',
        };
    }

    /** @deprecated Use executorLabel() and oversightManager for display. */
    public function managerLabel(): string
    {
        return $this->executorLabel();
    }
}
