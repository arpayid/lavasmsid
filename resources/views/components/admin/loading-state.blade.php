{{-- Loading state: message, spinner --}}
@props(['message' => 'Memuat...'])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="relative h-12 w-12">
        <div class="absolute inset-0 animate-spin rounded-full border-4 border-slate-200"></div>
        <div class="absolute inset-0 animate-spin rounded-full border-4 border-transparent border-t-indigo-600" style="animation-duration: 0.8s;"></div>
    </div>
    <p class="mt-4 text-sm text-slate-500">{{ $message }}</p>
</div>
