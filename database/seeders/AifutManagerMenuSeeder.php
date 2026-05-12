<?php

namespace Database\Seeders;

use App\Models\Aifut\AifutMenuItem;
use App\Models\Aifut\AifutMenuRule;
use Illuminate\Database\Seeder;

class AifutManagerMenuSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['code' => 'aifut.dashboard', 'actor_role' => 'superadmin', 'title' => 'AIFUT Dashboard', 'route_name' => 'dashboard.admin.aifut.manager-menu.index', 'icon' => 'layout-dashboard', 'sort_order' => 10],
            ['code' => 'aifut.manager-menu', 'actor_role' => 'superadmin', 'title' => 'Manager Menu', 'route_name' => 'dashboard.admin.aifut.manager-menu.index', 'icon' => 'menu-2', 'sort_order' => 20],
            ['code' => 'aifut.storage-policy', 'actor_role' => 'admin', 'title' => 'Storage Policy', 'url' => '#', 'icon' => 'database', 'sort_order' => 30],
            ['code' => 'aifut.domain-policy', 'actor_role' => 'admin', 'title' => 'Domain Policy', 'url' => '#', 'icon' => 'world', 'sort_order' => 40],
            ['code' => 'aifut.backup-export', 'actor_role' => 'user', 'title' => 'Backup & Export', 'url' => '#', 'icon' => 'archive', 'sort_order' => 50],
        ];

        foreach ($items as $item) {
            AifutMenuItem::query()->updateOrCreate(
                ['code' => $item['code']],
                $item + ['source_system' => 'aifut-bridge', 'is_visible' => true, 'is_enabled' => true]
            );
        }

        $rules = [
            ['menu_code' => 'aifut.storage-policy', 'scope_type' => 'global', 'actor_role' => 'admin', 'plan_code' => 'managed', 'storage_mode' => 'shared-aifut-storage', 'is_visible' => true, 'is_enabled' => true],
            ['menu_code' => 'aifut.domain-policy', 'scope_type' => 'global', 'actor_role' => 'admin', 'domain_mode' => 'aifut-provided', 'is_visible' => true, 'is_enabled' => true],
            ['menu_code' => 'aifut.backup-export', 'scope_type' => 'global', 'actor_role' => 'user', 'feature_code' => 'backup-export', 'is_visible' => true, 'is_enabled' => true],
        ];

        foreach ($rules as $rule) {
            $itemId = AifutMenuItem::query()->where('code', $rule['menu_code'])->value('id');
            if (! $itemId) {
                continue;
            }

            AifutMenuRule::query()->updateOrCreate(
                [
                    'menu_item_id' => $itemId,
                    'scope_type' => $rule['scope_type'],
                    'actor_role' => $rule['actor_role'],
                    'plan_code' => $rule['plan_code'] ?? null,
                    'feature_code' => $rule['feature_code'] ?? null,
                    'storage_mode' => $rule['storage_mode'] ?? null,
                    'domain_mode' => $rule['domain_mode'] ?? null,
                ],
                [
                    'source_system' => 'aifut-core',
                    'is_visible' => $rule['is_visible'],
                    'is_enabled' => $rule['is_enabled'],
                ]
            );
        }
    }
}
