<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_email',
        'status',
    ];

    /**
     * Get the users (college admins) for the college.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the college admins for the college.
     */
    public function collegeAdmins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'COLLEGE_ADMIN');
    }

    /**
     * Get the events for the college.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the vendors for the college.
     */
    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    /**
     * Get the activity logs for the college.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the reports for the college.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
