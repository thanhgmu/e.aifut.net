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
            <label class="mb-2 block text-sm font-medium">{{ __('Menu Item') }}</label>
            <select class="form-select" name="menu_item_id">
                @foreach ($menuItems as $menuItem)
                    <option value="{{ $menuItem->id }}" @selected((string) old('menu_item_id', $item->menu_item_id) === (string) $menuItem->id)>
                        {{ $menuItem->code }} — {{ $menuItem->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-forms.input id="scope_type" name="scope_type" label="{{ __('Scope Type') }}" size="lg" required value="{{ old('scope_type', $item->scope_type ?: 'global') }}" />
        <x-forms.input id="scope_key" name="scope_key" label="{{ __('Scope Key') }}" size="lg" value="{{ old('scope_key', $item->scope_key) }}" />
        <x-forms.input id="actor_role" name="actor_role" label="{{ __('Actor Role') }}" size="lg" required value="{{ old('actor_role', $item->actor_role ?: 'user') }}" />
        <x-forms.input id="plan_code" name="plan_code" label="{{ __('Plan Code') }}" size="lg" value="{{ old('plan_code', $item->plan_code) }}" />
        <x-forms.input id="feature_code" name="feature_code" label="{{ __('Feature Code') }}" size="lg" value="{{ old('feature_code', $item->feature_code) }}" />
        <x-forms.input id="storage_mode" name="storage_mode" label="{{ __('Storage Mode') }}" size="lg" value="{{ old('storage_mode', $item->storage_mode) }}" />
        <x-forms.input id="domain_mode" name="domain_mode" label="{{ __('Domain Mode') }}" size="lg" value="{{ old('domain_mode', $item->domain_mode) }}" />
        <x-forms.input id="source_system" name="source_system" label="{{ __('Source System') }}" size="lg" required value="{{ old('source_system', $item->source_system ?: 'aifut-core') }}" />
        <x-forms.input id="sort_order" name="sort_order" type="number" label="{{ __('Override Sort Order') }}" size="lg" value="{{ old('sort_order', $item->sort_order) }}" />
        <x-forms.input id="conditions" name="conditions" type="textarea" rows="6" label="{{ __('Conditions JSON') }}">{{ old('conditions', $item->conditions ? json_encode($item->conditions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</x-forms.input>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', $item->is_visible ?? true))> {{ __('Visible') }}</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_enabled" value="1" @checked(old('is_enabled', $item->is_enabled ?? true))> {{ __('Enabled') }}</label>

        <x-button size="lg" type="submit">{{ __('Save') }}</x-button>
    </form>
@endsection
