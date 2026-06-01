@props(['label', 'value', 'icon' => null, 'trend' => null, 'trendUp' => true])

<div class="rounded-2xl bg-white p-6 shadow-soft ring-1 ring-slate-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $value }}</h3>
            @if($trend)
            <div class="mt-2 flex items-center gap-1">
                <span class="text-xs font-medium {{ $trendUp ? 'text-success-600' : 'text-danger-500' }}">
                    {{ $trendUp ? '↑' : '↓' }} {{ $trend }}
                </span>
                <span class="text-xs text-slate-400">vs bln lalu</span>
            </div>
            @endif
        </div>
        @if($icon)
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
            {!! $icon !!}
        </div>
        @endif
    </div>
</div>
