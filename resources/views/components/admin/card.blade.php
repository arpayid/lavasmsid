<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200']) }}>
    @if(isset($header) || isset($title))
    <div class="mb-5 flex items-center justify-between">
        <div>
            @if(isset($title))
            <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
            @endif
            @if(isset($description))
            <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
            @endif
        </div>
        @if(isset($header))
            {{ $header }}
        @endif
    </div>
    @endif

    {{ $slot }}

    @if(isset($footer))
    <div class="mt-5 border-t border-slate-100 pt-4 flex items-center justify-end gap-3">
        {{ $footer }}
    </div>
    @endif
</div>
