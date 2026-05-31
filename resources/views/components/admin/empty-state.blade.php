{{-- Empty state: when no data --}}
<div class="text-center py-12">
    <div class="flex items-center justify-center mb-6">
        {!! $icon !!}
    </div>
    <h2 class="mb-4 text-xl font-bold text-slate-800">{{ $title }}</h2>
    <p class="mb-6 text-slate-500 max-w-xl">{{ $message }}</p>
    @if($actionUrl && $actionText)
    <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-md shadow-md hover:bg-indigo-700">
        {{ $actionText }}
    </a>
    @endif
</div>
