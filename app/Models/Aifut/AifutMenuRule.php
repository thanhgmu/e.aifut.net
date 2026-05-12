<?php

namespace App\Models\Aifut;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AifutMenuRule extends Model
{
    protected $table = 'aifut_menu_rules';

    protected $fillable = [
        'menu_item_id',
        'scope_type',
        'scope_key',
        'actor_role',
        'plan_code',
        'feature_code',
        'storage_mode',
        'domain_mode',
        'source_system',
        'is_visible',
        'is_enabled',
        'sort_order',
        'conditions',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_enabled' => 'boolean',
        'conditions' => 'array',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(AifutMenuItem::class, 'menu_item_id');
    }
}
