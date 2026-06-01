@props(['type' => 'info'])

@php
$variants = [
    'info' => 'bg-info-50 border-info-500 text-info-600',
    'success' => 'bg-success-50 border-success-500 text-success-600',
    'warning' => 'bg-warning-50 border-warning-500 text-warning-600',
    'danger' => 'bg-danger-50 border-danger-500 text-danger-600',
];
@endphp

<div class="rounded-lg border-l-4 p-4 {{ $variants[$type] }}" {{ $attributes }}>
    {{ $slot }}
</div>
