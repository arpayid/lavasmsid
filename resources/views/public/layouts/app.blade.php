<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ $settings->school_name ?? config('app.name') }}</title>
    @if(isset($settings->favicon_path))
        <link rel="icon" type="image/png" href="{{ Storage::url($settings->favicon_path) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                        @if(isset($settings->logo_path) && $settings->logo_path)
                            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-12 w-auto">
                        @else
                            <div class="h-10 w-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">L</div>
                        @endif
                        <div>
                            <span class="font-bold text-xl text-slate-900 leading-tight block">{{ $settings->school_name ?? config('app.name') }}</span>
                        </div>
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('public.home') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Beranda</a>
                    <a href="{{ route('public.profile') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Profil</a>
                    <a href="{{ route('public.news') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Berita</a>
                    <a href="{{ route('public.events') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Agenda</a>
                    <a href="{{ route('public.gallery') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Galeri</a>
                    <a href="{{ route('public.contact') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Kontak</a>

                    @if(Route::has('admin.ppdb.index'))
                    <a href="/ppdb" class="ml-4 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        Info PPDB
                    </a>
                    @endif
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 py-2">Login</a>
                </div>

                {{-- Mobile menu button --}}
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-slate-100" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 shadow-lg">
                <a href="{{ route('public.home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-50">Beranda</a>
                <a href="{{ route('public.profile') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-50">Profil</a>
                <a href="{{ route('public.news') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-50">Berita</a>
                <a href="{{ route('public.events') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-50">Agenda</a>
                <a href="{{ route('public.gallery') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-50">Galeri</a>
                <a href="{{ route('public.contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-50">Kontak</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-50 border-t mt-2 pt-2">Login Portal</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        @if(isset($settings->logo_path) && $settings->logo_path)
                            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-10 w-auto opacity-90">
                        @endif
                        <span class="text-xl font-bold text-white">{{ $settings->school_name ?? config('app.name') }}</span>
                    </div>
                    <p class="text-sm text-slate-400 mb-6 max-w-sm">
                        {{ $settings->tagline ?? 'Sistem Manajemen Sekolah Modern Terpadu' }}
                    </p>
                    <div class="flex gap-4">
                        @if(isset($settings->facebook_url) && $settings->facebook_url)
                        <a href="{{ $settings->facebook_url }}" class="text-slate-400 hover:text-white transition-colors" target="_blank" rel="noopener">FB</a>
                        @endif
                        @if(isset($settings->instagram_url) && $settings->instagram_url)
                        <a href="{{ $settings->instagram_url }}" class="text-slate-400 hover:text-white transition-colors" target="_blank" rel="noopener">IG</a>
                        @endif
                        @if(isset($settings->youtube_url) && $settings->youtube_url)
                        <a href="{{ $settings->youtube_url }}" class="text-slate-400 hover:text-white transition-colors" target="_blank" rel="noopener">YT</a>
                        @endif
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('public.profile') }}" class="hover:text-white transition-colors">Profil Sekolah</a></li>
                        <li><a href="{{ route('public.departments') }}" class="hover:text-white transition-colors">Jurusan</a></li>
                        <li><a href="{{ route('public.achievements') }}" class="hover:text-white transition-colors">Prestasi</a></li>
                        <li><a href="{{ route('public.facilities') }}" class="hover:text-white transition-colors">Fasilitas</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3 text-sm">
                        @if(isset($settings->school_address))
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $settings->school_address }}</span>
                        </li>
                        @endif
                        @if(isset($settings->school_phone))
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.94.86l-.76 3.29a1 1 0 01-1.17.73l-3.23-.96a8 8 0 006.18 6.18l.96-3.23a1 1 0 01.73-1.17l3.29.76a1 1 0 01.86 1.94L18.42 9.06a2 2 0 01-2 2H3a2 2 0 01-2-2V5z"/></svg>
                            <span>{{ $settings->school_phone }}</span>
                        </li>
                        @endif
                        @if(isset($settings->school_email))
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $settings->school_email }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $settings->school_name ?? config('app.name') }}. All rights reserved.</p>
                <p>Built with LavaSMSID</p>
            </div>
        </div>
    </footer>
</body>
</html>
