@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('AIFUT Menu Rules'))
@section('titlebar_actions')
    <x-button href="{{ route('dashboard.admin.aifut.manager-menu.rules.create') }}">{{ __('Create Rule') }}</x-button>
@endsection
@section('content')
    <div class="py-10">
        <x-table>
            <x-slot:head>
                <tr>
                    <th>{{ __('Menu Item') }}</th>
                    <th>{{ __('Scope') }}</th>
                    <th>{{ __('Actor') }}</th>
                    <th>{{ __('Policy Dimensions') }}</th>
                    <th>{{ __('State') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </x-slot:head>
            <x-slot:body>
                @foreach ($items as $entry)
                    <tr>
                        <td>{{ $entry->menuItem?->code }}</td>
                        <td>{{ $entry->scope_type }}{{ $entry->scope_key ? ' / ' . $entry->scope_key : '' }}</td>
                        <td>{{ $entry->actor_role }}</td>
                        <td>
                            <div class="text-xs opacity-70">plan={{ $entry->plan_code ?: '-' }}</div>
                            <div class="text-xs opacity-70">feature={{ $entry->feature_code ?: '-' }}</div>
                            <div class="text-xs opacity-70">storage={{ $entry->storage_mode ?: '-' }}</div>
                            <div class="text-xs opacity-70">domain={{ $entry->domain_mode ?: '-' }}</div>
                        </td>
                        <td>{{ $entry->is_visible ? 'visible' : 'hidden' }} / {{ $entry->is_enabled ? 'enabled' : 'disabled' }}</td>
                        <td class="text-end">
                            <x-button class="size-9" variant="ghost-shadow" size="none" href="{{ route('dashboard.admin.aifut.manager-menu.rules.edit', $entry->id) }}" title="{{ __('Edit') }}">
                                <x-tabler-pencil class="size-4" />
                            </x-button>
                        </td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-table>
    </div>
@endsection
