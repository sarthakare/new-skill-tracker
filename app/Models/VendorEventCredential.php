<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorEventCredential extends Model
{
    protected $fillable = [
        'vendor_id',
        'event_id',
        'college_id',
        'username',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }
}
