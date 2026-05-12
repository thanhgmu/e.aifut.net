<?php

namespace App\Services\Aifut;

use App\Models\Aifut\AifutMenuItem;
use App\Models\Aifut\AifutTenantBinding;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class MenuResolverService
{
    public function resolve(array $context = []): Collection
    {
        if (! Schema::hasTable('aifut_menu_items')) {
            return collect();
        }

        $actorRole = (string) ($context['actor_role'] ?? 'user');
        $binding = $this->findBinding($context);

        $items = AifutMenuItem::query()
            ->with('rules')
            ->where('actor_role', $actorRole)
            ->where('is_enabled', true)
            ->orderBy('sort_order')
            ->get();

        return $items
            ->map(fn (AifutMenuItem $item) => $this->applyRules($item, $binding, $context))
            ->filter(fn (array $item) => $item['is_visible'] && $item['is_enabled'])
            ->values();
    }

    public function preview(): array
    {
        return [
            'superadmin' => $this->resolve(['actor_role' => 'superadmin'])->take(12)->all(),
            'admin' => $this->resolve(['actor_role' => 'admin'])->take(12)->all(),
            'user' => $this->resolve(['actor_role' => 'user'])->take(12)->all(),
        ];
    }

    protected function findBinding(array $context): ?AifutTenantBinding
    {
        if (! Schema::hasTable('aifut_tenant_bindings')) {
            return null;
        }

        $userId = $context['user_id'] ?? null;
        $tenantCode = $context['tenant_code'] ?? null;

        $query = AifutTenantBinding::query();

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        if ($tenantCode !== null) {
            $query->where('tenant_code', $tenantCode);
        }

        return $query->first();
    }

    protected function applyRules(AifutMenuItem $item, ?AifutTenantBinding $binding, array $context): array
    {
        $resolved = [
            'id' => $item->id,
            'code' => $item->code,
            'title' => $item->title,
            'route_name' => $item->route_name,
            'url' => $item->url,
            'icon' => $item->icon,
            'parent_id' => $item->parent_id,
            'sort_order' => $item->sort_order,
            'is_visible' => $item->is_visible,
            'is_enabled' => $item->is_enabled,
            'source_system' => $item->source_system,
        ];

        foreach ($item->rules as $rule) {
            if (! $this->ruleMatches($rule->toArray(), $binding, $context)) {
                continue;
            }

            $resolved['is_visible'] = (bool) $rule->is_visible;
            $resolved['is_enabled'] = (bool) $rule->is_enabled;
            $resolved['sort_order'] = $rule->sort_order ?? $resolved['sort_order'];
        }

        return $resolved;
    }

    protected function ruleMatches(array $rule, ?AifutTenantBinding $binding, array $context): bool
    {
        $scopeKey = $rule['scope_key'] ?? null;
        $planCode = $context['plan_code'] ?? $binding?->plan_code;
        $storageMode = $context['storage_mode'] ?? $binding?->storage_mode;
        $domainMode = $context['domain_mode'] ?? $binding?->domain_mode;
        $featureCodes = $context['feature_codes'] ?? [];

        if (! in_array($rule['scope_type'], ['global', 'tenant', 'workspace', 'user'], true)) {
            return false;
        }

        if ($rule['scope_type'] === 'tenant' && $scopeKey !== null && $scopeKey !== ($context['tenant_code'] ?? $binding?->tenant_code)) {
            return false;
        }

        if ($rule['scope_type'] === 'workspace' && $scopeKey !== null && $scopeKey !== ($context['workspace_code'] ?? $binding?->workspace_code)) {
            return false;
        }

        if ($rule['scope_type'] === 'user' && $scopeKey !== null && (string) $scopeKey !== (string) ($context['user_id'] ?? '')) {
            return false;
        }

        if (! empty($rule['plan_code']) && $rule['plan_code'] !== $planCode) {
            return false;
        }

        if (! empty($rule['storage_mode']) && $rule['storage_mode'] !== $storageMode) {
            return false;
        }

        if (! empty($rule['domain_mode']) && $rule['domain_mode'] !== $domainMode) {
            return false;
        }

        if (! empty($rule['feature_code']) && ! in_array($rule['feature_code'], $featureCodes, true)) {
            return false;
        }

        return true;
    }
}
