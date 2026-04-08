<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the college that the user belongs to.
     */
    public function college()
    {
        return $this->belongsTo(College::class);
    }

    /**
     * Department (for students), from college-defined departments.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'SUPER_ADMIN';
    }

    /**
     * Check if user is College Admin.
     */
    public function isCollegeAdmin(): bool
    {
        return $this->role === 'COLLEGE_ADMIN';
    }

    /**
     * Check if user is a Student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'STUDENT';
    }

    /**
     * Get the events that the user is assigned to.
     */
    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the event user assignments.
     */
    public function eventUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventUser::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'college_id',
        'department_id',
        'roll_number',
        'mobile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
