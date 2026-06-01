@props(['icon' => null, 'title' => 'Tidak ada data', 'message' => '', 'actionLabel' => null, 'actionUrl' => null])

<div class="flex flex-col items-center justify-center py-12 text-center">
    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        @endif
    </div>
    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
    <p class="mt-1 text-sm text-slate-500">{{ $message }}</p>
    @if($actionLabel && $actionUrl)
    <a href="{{ $actionUrl }}" class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
        {{ $actionLabel }}
    </a>
    @endif
</div>
