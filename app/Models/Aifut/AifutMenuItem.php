<?php

namespace App\Models\Aifut;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AifutMenuItem extends Model
{
    protected $table = 'aifut_menu_items';

    protected $fillable = [
        'code',
        'source_system',
        'actor_role',
        'title',
        'route_name',
        'url',
        'icon',
        'parent_id',
        'sort_order',
        'is_visible',
        'is_enabled',
        'meta',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_enabled' => 'boolean',
        'meta' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(AifutMenuRule::class, 'menu_item_id');
    }
}
