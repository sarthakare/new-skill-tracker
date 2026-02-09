<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventVendor extends Model
{
    protected $fillable = [
        'event_id',
        'vendor_id',
        'college_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }
}
