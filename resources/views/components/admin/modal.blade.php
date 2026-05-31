{{-- Modal: reusable modal with header, body, footer --}}
<div x-show="open" x-cloak x-transition:x-enter="transition ease-out duration-100"
     x-enter-start="transform opacity-0 scale-95"
     x-enter-end="transform opacity-100 scale-100"
     x-leave="transition ease-in duration-75"
     x-leave-start="transform opacity-100 scale-100"
     x-leave-end="transform opacity-0 scale-95"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

        {{-- Modal panel --}}
        <div class="relative bg-white rounded-lg shadow-xl p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-900">{{ $title }}</h2>
                <button @click="open = false"
                        class="rounded-md p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="space-y-4">
                {!! $slot !!}
            </div>

            {{-- Footer --}}
            @if(isset($footer))
            <div class="mt-6 flex items-center justify-end space-x-3">
                {!! $footer !!}
            </div>
            @endif
        </div>
    </div>
</div>
