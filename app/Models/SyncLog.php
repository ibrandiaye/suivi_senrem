<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'records_count',
        'is_success',
        'error_message',
        'payload_summary',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'is_success' => 'boolean',
            'records_count' => 'integer',
            'payload_summary' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
