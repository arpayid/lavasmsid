{{-- Form input: label, name, type, value, error --}}
@props([
    'name',
    'label' => '',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'maxlength' => null,
    'minlength' => null,
    'pattern' => null,
    'icon' => null,
    'help' => null,
    'error' => null,
])

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
    @endif
    <div class="relative">
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value ?? '') }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($autofocus) autofocus @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($minlength) minlength="{{ $minlength }}" @endif
            @if($pattern) pattern="{{ $pattern }}" @endif
            {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition']) }}
        >
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                {!! $icon !!}
            </div>
        @endif
    </div>
    @if($error ?? $errors->has($name))
        <p class="text-sm text-red-600">{{ $error ?? $errors->first($name) }}</p>
    @endif
    @if($help && !($error ?? $errors->has($name)))
        <p class="text-xs text-slate-500">{{ $help }}</p>
    @endif
</div>
