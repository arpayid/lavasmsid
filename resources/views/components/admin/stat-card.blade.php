@props([
    'icon' => null,
    'value',
    'label',
    'trend' => null,
    'color' => 'blue',  // blue, green, purple, amber, red, indigo
])

@php
$colors = [
    'blue' => ['icon' => 'bg-blue-50 text-blue-600', 'trend' => 'text-blue-600 bg-blue-50'],
    'green' => ['icon' => 'bg-emerald-50 text-emerald-600', 'trend' => 'text-emerald-600 bg-emerald-50'],
    'purple' => ['icon' => 'bg-purple-50 text-purple-600', 'trend' => 'text-purple-600 bg-purple-50'],
    'amber' => ['icon' => 'bg-amber-50 text-amber-600', 'trend' => 'text-amber-600 bg-amber-50'],
    'red' => ['icon' => 'bg-red-50 text-red-600', 'trend' => 'text-red-600 bg-red-50'],
    'indigo' => ['icon' => 'bg-indigo-50 text-indigo-600', 'trend' => 'text-indigo-600 bg-indigo-50'],
];

$palette = $colors[$color] ?? $colors['blue'];
@endphp

<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
    @if($icon || $trend)
    <div class="flex items-center justify-between mb-3">
        @if($icon)
        <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $palette['icon'] }}">
            {!! $icon !!}
        </div>
        @else
        <div></div>
        @endif
        @if($trend)
        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $palette['trend'] }}">
            {{ $trend }}
        </span>
        @endif
    </div>
    @endif
    <div class="text-3xl font-bold text-slate-900">{{ $value }}</div>
    <div class="mt-1 text-sm font-medium text-slate-500 capitalize">{{ $label }}</div>
</div>
