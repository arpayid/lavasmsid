@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'href' => null, 'disabled' => false])

@php
$base = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
$sizes = ['sm' => 'px-3 py-1.5 text-xs', 'md' => 'px-4 py-2.5 text-sm', 'lg' => 'px-6 py-3 text-base'];
$variants = [
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-sm',
    'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200 focus:ring-slate-500 border border-slate-300',
    'danger' => 'bg-danger-500 text-white hover:bg-danger-600 focus:ring-danger-500 shadow-sm',
    'success' => 'bg-success-500 text-white hover:bg-success-600 focus:ring-success-500 shadow-sm',
    'warning' => 'bg-warning-500 text-white hover:bg-warning-600 focus:ring-warning-500 shadow-sm',
    'ghost' => 'text-slate-600 hover:bg-slate-100 focus:ring-slate-500',
    'link' => 'text-primary-600 hover:text-primary-700 underline-offset-4 hover:underline',
];
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $base }} {{ $sizes[$size] }} {{ $variants[$variant] }}" {{ $disabled ? 'aria-disabled=true' : '' }} {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @if($disabled) disabled @endif class="{{ $base }} {{ $sizes[$size] }} {{ $variants[$variant] }}" {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
