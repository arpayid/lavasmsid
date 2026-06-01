{{-- Empty state: when no data --}}
@props(['title' => 'Tidak ada data', 'message' => '', 'icon' => null, 'actionUrl' => null, 'actionText' => null])

<div class="flex flex-col items-center justify-center py-12 text-center">
    @if($icon)
    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
        {!! $icon !!}
    </div>
    @endif
    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
    @if($message)
    <p class="mt-1 text-sm text-slate-500">{{ $message }}</p>
    @endif
    @if($actionUrl && $actionText)
    <a href="{{ $actionUrl }}" class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
        {{ $actionText }}
    </a>
    @endif
</div>
