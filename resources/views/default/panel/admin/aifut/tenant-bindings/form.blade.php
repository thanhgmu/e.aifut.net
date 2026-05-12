@extends('panel.layout.settings', ['disable_tblr' => true])
@section('title', $title)
@section('titlebar_actions', '')

@section('settings')
    <form class="flex flex-col gap-5" action="{{ $action }}" method="post">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div>
            <label class="mb-2 block text-sm font-medium">{{ __('User') }}</label>
            <select class="form-select" name="user_id">
                <option value="">{{ __('None') }}</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) old('user_id', $item->user_id) === (string) $user->id)>
                        #{{ $user->id }} — {{ $user->email ?: $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-forms.input id="tenant_code" name="tenant_code" label="{{ __('Tenant Code') }}" size="lg" required value="{{ old('tenant_code', $item->tenant_code) }}" />
        <x-forms.input id="workspace_code" name="workspace_code" label="{{ __('Workspace Code') }}" size="lg" value="{{ old('workspace_code', $item->workspace_code) }}" />
        <x-forms.input id="source_system" name="source_system" label="{{ __('Source System') }}" size="lg" required value="{{ old('source_system', $item->source_system ?: 'aifut-core') }}" />
        <x-forms.input id="plan_code" name="plan_code" label="{{ __('Plan Code') }}" size="lg" value="{{ old('plan_code', $item->plan_code) }}" />
        <x-forms.input id="storage_mode" name="storage_mode" label="{{ __('Storage Mode') }}" size="lg" value="{{ old('storage_mode', $item->storage_mode) }}" />
        <x-forms.input id="domain_mode" name="domain_mode" label="{{ __('Domain Mode') }}" size="lg" value="{{ old('domain_mode', $item->domain_mode) }}" />
        <x-forms.input id="capabilities" name="capabilities" type="textarea" rows="5" label="{{ __('Capabilities JSON') }}">{{ old('capabilities', $item->capabilities ? json_encode($item->capabilities, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</x-forms.input>
        <x-forms.input id="sync_meta" name="sync_meta" type="textarea" rows="5" label="{{ __('Sync Meta JSON') }}">{{ old('sync_meta', $item->sync_meta ? json_encode($item->sync_meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</x-forms.input>

        <x-button size="lg" type="submit">{{ __('Save') }}</x-button>
    </form>
@endsection
