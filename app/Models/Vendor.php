<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vendor extends Model
{
    protected $fillable = [
        'college_id',
        'name',
        'type',
        'contact_email',
        'contact_phone',
        'address',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_vendors')
            ->withTimestamps();
    }

    public function eventCredentials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VendorEventCredential::class);
    }
}
