{{-- Form textarea: label, name, value, rows, error, help --}}
@props(['name', 'label', 'value' => null, 'rows' => 4, 'placeholder' => null, 'required' => false, 'error' => null, 'help' => null])

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
    @endif
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition resize-y']) }}
    >{{ old($name, $value ?? '') }}</textarea>
    @if($error ?? $errors->has($name))
        <p class="text-sm text-red-600">{{ $error ?? $errors->first($name) }}</p>
    @endif
    @if($help && !($error ?? $errors->has($name)))
        <p class="text-xs text-slate-500">{{ $help }}</p>
    @endif
</div>
