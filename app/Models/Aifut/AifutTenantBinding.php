<?php

namespace App\Models\Aifut;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AifutTenantBinding extends Model
{
    protected $table = 'aifut_tenant_bindings';

    protected $fillable = [
        'user_id',
        'tenant_code',
        'workspace_code',
        'source_system',
        'plan_code',
        'storage_mode',
        'domain_mode',
        'capabilities',
        'sync_meta',
        'synced_at',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'sync_meta' => 'array',
        'synced_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
