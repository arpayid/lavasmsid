{{-- Toast: temporary notification --}}
<div x-show="show" x-cloak x-transition:x-enter="transition ease-out duration-100"
     x-enter-start="transform opacity-0 translate-y-4"
     x-enter-end="transform opacity-100 translate-y-0"
     x-leave="transition ease-in duration-75"
     x-leave-start="transform opacity-100"
     x-leave-end="transform opacity-0 translate-y-4"
     class="fixed z-50 w-full max-w-xs p-4 pointer-events-auto"
     :class="{'top-5': placement === 'top', 'bottom-5': placement === 'bottom'}"
     x-data="{ show: true, placement: '{{ $placement ?? 'top-right' }}' }"
     x-init="setTimeout(() => show = false, {{ $duration ?? 5000 }})">
    <div class="flex items-center w-full rounded-lg border {{ $type === 'success' ? 'border-green-500 bg-green-50' : ( $type === 'error' ? 'border-red-500 bg-red-50' : ( $type === 'warning' ? 'border-yellow-500 bg-yellow-50' : 'border-blue-500 bg-blue-50' ) }} ">
        <div class="flex-shrink-0">
            {{ $icon ?? ( $type === 'success' ? '<svg class=\"h-5 w-5 text-green-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>' : ( $type === 'error' ? '<svg class=\"h-5 w-5 text-red-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\"/></svg>' : ( $type === 'warning' ? '<svg class=\"h-5 w-5 text-yellow-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 9v2m0 4h.01m-6.938 4h13.856c1.132 0 2.182-.362 2.962-1.01l-.864-10.34a2 2 0 00-1.19-1.866H4.84a2 2 0 00-1.19 1.866z\"/></svg>' : '<svg class=\"h-5 w-5 text-blue-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13 16h-1v-4h-1m1-4h.01M20.888 18.094a3 3 0 10-4.236-4.236.948.053 1.051.191 1.342.494l1.798 1.798c-.145.283-.33.538-.465.758l-.793 2.732a1 1 0 00.293 1.318c.484.09 1.005.122 1.505.122 1.114 0 2.155-.665 2.714-1.566l-.376-2.578a1 1 0 00-1.554-.712l-.292-.848a1 1 0 00-1.476-.52L9.88 8.224a1 1 0 00-.694-.228L8.472 9.456a1 1 0 00-.314 1.832c-.559 1.043-.95 2.237-.95 3.398v.069z\"/></svg>' ) }}
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-slate-800">{{ $title }}</p>
            <p class="text-sm text-slate-600">{{ $message }}</p>
        </div>
        <button @click="show = false"
                class="ml-2 flex h-8 w-8 items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-500">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
