@props(['message' => 'Memuat data...'])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="relative h-12 w-12">
        <div class="absolute inset-0 rounded-full border-4 border-slate-200"></div>
        <div class="absolute inset-0 animate-spin rounded-full border-4 border-transparent border-t-primary-600"></div>
    </div>
    <p class="mt-4 text-sm text-slate-500">{{ $message }}</p>
</div>
