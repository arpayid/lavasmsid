@props(['src' => null, 'alt' => '', 'size' => 'md'])

@php
$sizes = ['sm' => 'h-8 w-8 text-xs', 'md' => 'h-10 w-10 text-sm', 'lg' => 'h-12 w-12 text-base', 'xl' => 'h-16 w-16 text-lg'];
@endphp

<div class="{{ $sizes[$size] }} rounded-full bg-primary-100 flex items-center justify-center font-medium text-primary-700 overflow-hidden {{ $attributes->get('class') }}">
    @if($src)
        <img src="{{ $src }}" alt="{{ $alt }}" class="h-full w-full object-cover">
    @else
        {{ strtoupper(substr($alt, 0, 1)) }}
    @endif
</div>
