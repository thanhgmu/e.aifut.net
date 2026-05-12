@extends('panel.layout.settings', ['disable_tblr' => true])
@section('title', $title)
@section('titlebar_actions', '')

@section('settings')
    <form class="flex flex-col gap-5" action="{{ $action }}" method="post">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <x-forms.input id="code" name="code" label="{{ __('Code') }}" size="lg" required value="{{ old('code', $item->code) }}" />
        <x-forms.input id="title" name="title" label="{{ __('Title') }}" size="lg" required value="{{ old('title', $item->title) }}" />
        <x-forms.input id="source_system" name="source_system" label="{{ __('Source System') }}" size="lg" required value="{{ old('source_system', $item->source_system ?: 'aifut-bridge') }}" />
        <x-forms.input id="actor_role" name="actor_role" label="{{ __('Actor Role') }}" size="lg" required value="{{ old('actor_role', $item->actor_role ?: 'user') }}" />
        <x-forms.input id="route_name" name="route_name" label="{{ __('Route Name') }}" size="lg" value="{{ old('route_name', $item->route_name) }}" />
        <x-forms.input id="url" name="url" label="{{ __('URL') }}" size="lg" value="{{ old('url', $item->url) }}" />
        <x-forms.input id="icon" name="icon" label="{{ __('Icon') }}" size="lg" value="{{ old('icon', $item->icon) }}" />
        <x-forms.input id="sort_order" name="sort_order" type="number" label="{{ __('Sort Order') }}" size="lg" value="{{ old('sort_order', $item->sort_order ?? 0) }}" />

        <div>
            <label class="mb-2 block text-sm font-medium">{{ __('Parent Menu') }}</label>
            <select class="form-select" name="parent_id">
                <option value="">{{ __('None') }}</option>
                @foreach ($menuParents as $parent)
                    <option value="{{ $parent->id }}" @selected((string) old('parent_id', $item->parent_id) === (string) $parent->id)>
                        {{ $parent->code }} — {{ $parent->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-forms.input id="meta" name="meta" type="textarea" rows="6" label="{{ __('Meta JSON') }}">{{ old('meta', $item->meta ? json_encode($item->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</x-forms.input>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', $item->is_visible ?? true))> {{ __('Visible') }}</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_enabled" value="1" @checked(old('is_enabled', $item->is_enabled ?? true))> {{ __('Enabled') }}</label>

        <x-button size="lg" type="submit">{{ __('Save') }}</x-button>
    </form>
@endsection
