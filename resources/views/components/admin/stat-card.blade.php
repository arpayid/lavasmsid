{{-- Stat card: icon, value, label, trend --}}
@props(['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>', 'value', 'label', 'trend' => null, 'bgColor' => 'bg-indigo-100', 'textColor' => 'text-indigo-600'])

<div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200 transition hover:shadow-md">
    <div class="flex items-center justify-between mb-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $bgColor }} {{ $textColor }}">
            {!! $icon !!}
        </div>
        @if($trend)
            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $trend['color'] }}">{{ $trend['label'] }}</span>
        @endif
    </div>
    <div class="text-3xl font-bold text-slate-900">{{ $value }}</div>
    <div class="mt-1 text-sm text-slate-500 capitalize">{{ $label }}</div>
</div>
