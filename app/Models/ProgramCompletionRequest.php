<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramCompletionRequest extends Model
{
    protected $fillable = [
        'college_id',
        'program_id',
        'requested_by_credential_id',
        'status',
        'notes',
        'attachments',
        'reviewed_by_user_id',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(ProgramManagerCredential::class, 'requested_by_credential_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}
