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
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"
         @click="sidebarOpen = false"></div>

    <div class="min-h-screen lg:flex">

        {{-- Sidebar --}}
        @include('components.admin.sidebar')

        {{-- Main content --}}
        <div class="flex-1 lg:pl-0" :class="sidebarCollapsed ? '' : ''">

            {{-- Topbar --}}
            @include('components.admin.topbar')

            {{-- Page heading --}}
            @if(isset($heading) && $heading)
                <div class="border-b border-slate-200 bg-white px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-slate-900">{{ $heading }}</h1>
                            @if(isset($breadcrumb))
                                <div class="mt-1 text-sm text-slate-500">
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                                    @if(is_string($breadcrumb))
                                        <span class="mx-1">/</span>
                                        <span>{{ $breadcrumb }}</span>
                                    @elseif(is_array($breadcrumb))
                                        @foreach($breadcrumb as $bc)
                                            <span class="mx-1">/</span>
                                            @if($loop->last)
                                                <span>{{ $bc }}</span>
                                            @else
                                                <span>{{ $bc }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            {{ $actions ?? '' }}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Session flash messages --}}
            @if(session('success'))
                <div class="mx-6 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <x-admin.toast type="success" :message="session('success')" />
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <x-admin.toast type="error" :message="session('error')" />
                </div>
            @endif

            {{-- Page content --}}
            <section class="p-6">
                {{ $slot }}
            </section>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
