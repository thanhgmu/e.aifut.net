<?php

namespace App\Models\Aifut;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AifutChangeRequest extends Model
{
    protected $table = 'aifut_change_requests';

    protected $fillable = [
        'user_id',
        'request_type',
        'status',
        'tenant_code',
        'target_system',
        'payload',
        'response_payload',
        'submitted_at',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'response_payload' => 'array',
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
