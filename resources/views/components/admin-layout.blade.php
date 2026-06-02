@props(['title' => 'Admin', 'heading' => 'Dashboard'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — LavaSMSID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak
         class="fixed inset-0 z-40 bg-black/40 lg:hidden"
         @click="sidebarOpen = false"></div>

    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        @include('components.admin.sidebar')

        {{-- Main content --}}
        <div class="flex-1 flex flex-col min-w-0 lg:ml-72"
             :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-72'">

            {{-- Topbar --}}
            @include('components.admin.topbar')

            {{-- Page heading --}}
            @if(isset($heading) && $heading)
                <div class="bg-white border-b border-slate-200 px-6 py-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h1 class="text-xl font-bold text-slate-900">{{ $heading }}</h1>
                            @if(isset($breadcrumb))
                                <div class="mt-1 text-sm text-slate-500">
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
                                    @if(is_string($breadcrumb))
                                        <span class="mx-1.5 text-slate-300">/</span>
                                        <span class="text-slate-600">{{ $breadcrumb }}</span>
                                    @elseif(is_array($breadcrumb))
                                        @foreach($breadcrumb as $bc)
                                            <span class="mx-1.5 text-slate-300">/</span>
                                            @if($loop->last)
                                                <span class="text-slate-600">{{ $bc }}</span>
                                            @else
                                                <span class="text-slate-500">{{ $bc }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if(isset($actions))
                        <div class="flex items-center gap-3 shrink-0">
                            {{ $actions }}
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Session flash messages --}}
            @if(session('success'))
                <div class="px-6 pt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <x-admin.toast type="success" :message="session('success')" />
                </div>
            @endif

            @if(session('error'))
                <div class="px-6 pt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <x-admin.toast type="error" :message="session('error')" />
                </div>
            @endif

            {{-- Page content --}}
            <section class="flex-1 p-6 lg:p-8">
                {{ $slot }}
            </section>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
