{{-- Topbar: clean, light, with profile and mobile toggle --}}
<header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-sm">
    <div class="flex items-center justify-between px-4 lg:px-6 h-16">

        {{-- Left: Mobile hamburger --}}
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden inline-flex items-center justify-center h-9 w-9 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        {{-- Right: Notifications + Quick Action + Profile --}}
        <div class="flex items-center gap-2">

            {{-- Notification icon --}}
            <button class="relative inline-flex items-center justify-center h-9 w-9 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.023-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
            </button>

            {{-- Quick action: new message --}}
            @if(Route::has('admin.communication.messages.create'))
            <a href="{{ route('admin.communication.messages.create') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden md:inline">Pesan Baru</span>
            </a>
            @endif

            {{-- User profile --}}
            <div class="flex items-center gap-2.5 ml-3 pl-3 border-l border-slate-200">
                <div class="hidden sm:block text-right">
                    <div class="text-sm font-semibold text-slate-900 leading-tight">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500 capitalize">{{ auth()->user()->roles->first()->name ?? 'User' }}</div>
                </div>
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700 shrink-0">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 hover:text-red-600 transition-colors" title="Logout">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
