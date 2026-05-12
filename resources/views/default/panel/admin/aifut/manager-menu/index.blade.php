@extends('panel.layout.app')

@section('title', __('AIFUT Manager Menu'))

@section('content')
    <div class="py-10">
        <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="mb-2 text-2xl font-semibold">{{ __('AIFUT Manager Menu') }}</h1>
                <p class="text-sm text-heading-foreground/70">
                    {{ __('Policy-driven menu orchestration for AIFUT bridge. This screen is intentionally minimal until PHP/Git/deploy validation is available.') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-button href="{{ route('dashboard.admin.aifut.manager-menu.items.create') }}">{{ __('Create Menu Item') }}</x-button>
                <x-button href="{{ route('dashboard.admin.aifut.manager-menu.rules.index') }}" variant="secondary">{{ __('Manage Rules') }}</x-button>
            </div>
        </div>

        <div class="mb-6 rounded-xl border p-5">
            <div class="mb-3 text-sm font-medium">{{ __('System status') }}</div>
            <ul class="list-disc space-y-1 ps-5 text-sm">
                <li>{{ __('Bridge tables ready:') }} <strong>{{ $tablesReady ? 'yes' : 'no' }}</strong></li>
                <li>{{ __('AIFUT-core remains source of truth for plan/quota/storage/domain policy.') }}</li>
                <li>{{ __('e.aifut.net may mirror state and submit change requests.') }}</li>
            </ul>
        </div>

        <div class="mb-6 grid gap-4 md:grid-cols-4">
            @foreach ($stats as $label => $value)
                <div class="rounded-xl border p-4">
                    <div class="text-xs uppercase tracking-wide text-heading-foreground/60">{{ str_replace('_', ' ', $label) }}</div>
                    <div class="mt-2 text-2xl font-semibold">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div class="mb-6 rounded-xl border p-5">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">{{ __('Menu Items') }}</h2>
                <span class="text-xs text-heading-foreground/60">{{ __('Latest registry snapshot') }}</span>
            </div>

            @if (count($items) === 0)
                <p class="text-sm text-heading-foreground/70">{{ __('No menu items yet. Seed or create items first.') }}</p>
            @else
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Route / URL') }}</th>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('State') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </x-slot:head>
                    <x-slot:body>
                        @foreach ($items as $entry)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $entry->code }}</div>
                                    <div class="text-xs opacity-60">{{ $entry->title }}</div>
                                </td>
                                <td>{{ $entry->actor_role }}</td>
                                <td>{{ $entry->route_name ?: $entry->url ?: '-' }}</td>
                                <td>#{{ $entry->sort_order }}</td>
                                <td>{{ $entry->is_visible ? 'visible' : 'hidden' }} / {{ $entry->is_enabled ? 'enabled' : 'disabled' }}</td>
                                <td class="text-end">
                                    <x-button class="size-9" variant="ghost-shadow" size="none" href="{{ route('dashboard.admin.aifut.manager-menu.items.edit', $entry->id) }}" title="{{ __('Edit') }}">
                                        <x-tabler-pencil class="size-4" />
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot:body>
                </x-table>
            @endif
        </div>

        <div class="mb-6 rounded-xl border p-5">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">{{ __('Recent Rules') }}</h2>
                <x-button href="{{ route('dashboard.admin.aifut.manager-menu.rules.create') }}" variant="secondary">{{ __('Create Rule') }}</x-button>
            </div>

            @if (count($rules) === 0)
                <p class="text-sm text-heading-foreground/70">{{ __('No rules yet.') }}</p>
            @else
                <ul class="space-y-2 text-sm">
                    @foreach ($rules as $rule)
                        <li class="rounded-lg bg-heading-foreground/5 px-3 py-3">
                            <div class="font-medium">{{ $rule->menuItem?->code }} · {{ $rule->actor_role }}</div>
                            <div class="text-xs text-heading-foreground/60">
                                {{ $rule->scope_type }}{{ $rule->scope_key ? ' / ' . $rule->scope_key : '' }} · plan={{ $rule->plan_code ?: '-' }} · feature={{ $rule->feature_code ?: '-' }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($preview as $role => $items)
                <div class="rounded-xl border p-5">
                    <h2 class="mb-3 text-lg font-semibold">{{ strtoupper($role) }}</h2>
                    @if (count($items) === 0)
                        <p class="text-sm text-heading-foreground/70">{{ __('No resolved menu items yet.') }}</p>
                    @else
                        <ul class="space-y-2 text-sm">
                            @foreach ($items as $item)
                                <li class="rounded-lg bg-heading-foreground/5 px-3 py-2">
                                    <div class="font-medium">{{ $item['title'] ?: $item['code'] }}</div>
                                    <div class="text-xs text-heading-foreground/60">
                                        {{ $item['code'] }} · {{ $item['source_system'] }} · #{{ $item['sort_order'] }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
