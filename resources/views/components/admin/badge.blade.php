{{-- Badge: small label for status --}}
@props(['label' => '', 'variant' => 'default', 'class' => ''])

@php
    $variants = [
        'default' => 'bg-slate-100 text-slate-700',
        'success' => 'bg-emerald-100 text-emerald-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'danger' => 'bg-red-100 text-red-700',
        'info' => 'bg-indigo-100 text-indigo-700',
        'primary' => 'bg-indigo-100 text-indigo-700',
    ];
    $badgeClass = $variants[$variant] ?? $variants['default'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }} {{ $class }}">{{ $label }}</span>
