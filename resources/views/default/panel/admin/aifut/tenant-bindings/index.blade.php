@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('AIFUT Tenant Bindings'))
@section('titlebar_actions')
    <x-button href="{{ route('dashboard.admin.aifut.tenant-bindings.create') }}">{{ __('Create Tenant Binding') }}</x-button>
@endsection
@section('content')
    <div class="py-10">
        <x-table>
            <x-slot:head>
                <tr>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Tenant') }}</th>
                    <th>{{ __('Workspace') }}</th>
                    <th>{{ __('Plan') }}</th>
                    <th>{{ __('Storage / Domain') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </x-slot:head>
            <x-slot:body>
                @foreach ($items as $entry)
                    <tr>
                        <td>{{ $entry->user?->email ?: '-' }}</td>
                        <td>{{ $entry->tenant_code }}</td>
                        <td>{{ $entry->workspace_code ?: '-' }}</td>
                        <td>{{ $entry->plan_code ?: '-' }}</td>
                        <td>{{ ($entry->storage_mode ?: '-') . ' / ' . ($entry->domain_mode ?: '-') }}</td>
                        <td class="text-end">
                            <x-button class="size-9" variant="ghost-shadow" size="none" href="{{ route('dashboard.admin.aifut.tenant-bindings.edit', $entry->id) }}" title="{{ __('Edit') }}">
                                <x-tabler-pencil class="size-4" />
                            </x-button>
                        </td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-table>
    </div>
@endsection
