{{-- Error state: title, message, action button --}}
@props(['title' => 'Terjadi Kesalahan', 'message' => 'Silakan coba lagi atau hubungi administrator.', 'actionUrl' => null, 'actionLabel' => 'Kembali'])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-red-100 mb-6">
        <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
    <p class="mt-2 max-w-sm text-sm text-slate-500">{{ $message }}</p>
    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $actionLabel }}
        </a>
    @endif
</div>
