<div class="mb-6 md:mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
        @if(isset($breadcrumb))
        <nav class="mb-2 flex items-center gap-2 text-sm text-slate-500">
            {{ $breadcrumb }}
        </nav>
        @endif
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">{{ $title }}</h1>
        @if(isset($description))
        <p class="mt-1.5 text-sm text-slate-500">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
    <div class="flex shrink-0 flex-wrap items-center gap-3">
        {{ $actions }}
    </div>
    @endif
</div>
