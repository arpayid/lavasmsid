@props(['variant' => 'default', 'size' => 'md'])

@php
$base = 'inline-flex items-center font-medium rounded-full';
$sizes = ['sm' => 'px-2 py-0.5 text-[10px]', 'md' => 'px-2.5 py-1 text-xs', 'lg' => 'px-3 py-1.5 text-sm'];
$variants = [
    'default' => 'bg-slate-100 text-slate-700',
    'primary' => 'bg-primary-50 text-primary-700',
    'success' => 'bg-success-50 text-success-700',
    'warning' => 'bg-warning-50 text-warning-700',
    'danger' => 'bg-danger-50 text-danger-700',
    'info' => 'bg-info-50 text-info-700',
];
@endphp

<span class="{{ $base }} {{ $sizes[$size] }} {{ $variants[$variant] }}" {{ $attributes }}>
    {{ $slot }}
</span>
