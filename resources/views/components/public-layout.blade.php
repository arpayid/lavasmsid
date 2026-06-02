@php
    $settings = \App\Models\SchoolSetting::firstOrCreateDefault();
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $settings->school_name ?? 'LavaSMSID' }}</title>
    @if(isset($settings->favicon_path))
        <link rel="icon" type="image/png" href="{{ Storage::url($settings->favicon_path) }}">
    @endif
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased font-sans min-h-screen flex flex-col">
    {{-- Navbar --}}
    <nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur-md" x-data="{ mobileMenuOpen: false }">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                @if(isset($settings->logo_path) && $settings->logo_path)
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-10 w-auto">
                @else
                    <div class="h-10 w-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">L</div>
                @endif
                <span class="text-xl font-black text-slate-900 hidden sm:block">{{ $settings->school_name ?? 'LavaSMSID' }}</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="{{ route('public.home') }}" class="hover:text-blue-600 {{ request()->routeIs('public.home') ? 'text-blue-600' : '' }}">Beranda</a>
                <a href="{{ route('public.profile') }}" class="hover:text-blue-600 {{ request()->routeIs('public.profile') ? 'text-blue-600' : '' }}">Profil</a>
                <a href="{{ route('public.news') }}" class="hover:text-blue-600 {{ request()->routeIs('public.news*') ? 'text-blue-600' : '' }}">Berita</a>
                <a href="{{ route('public.events') }}" class="hover:text-blue-600 {{ request()->routeIs('public.events*') ? 'text-blue-600' : '' }}">Agenda</a>
                <a href="{{ route('public.gallery') }}" class="hover:text-blue-600 {{ request()->routeIs('public.gallery*') ? 'text-blue-600' : '' }}">Galeri</a>
                <a href="{{ route('public.achievements') }}" class="hover:text-blue-600 {{ request()->routeIs('public.achievements*') ? 'text-blue-600' : '' }}">Prestasi</a>
                <a href="{{ route('public.facilities') }}" class="hover:text-blue-600 {{ request()->routeIs('public.facilities*') ? 'text-blue-600' : '' }}">Fasilitas</a>
                <a href="{{ route('public.contact') }}" class="hover:text-blue-600 {{ request()->routeIs('public.contact') ? 'text-blue-600' : '' }}">Kontak</a>

                @if(Route::has('public.ppdb'))
                    <a href="{{ route('public.ppdb') }}" class="text-blue-600 font-bold hover:text-blue-800">PPDB</a>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">Portal Admin</a>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-slate-100" style="display: none;" x-collapse>
            <div class="px-4 py-3 space-y-1 shadow-lg">
                <a href="{{ route('public.home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Beranda</a>
                <a href="{{ route('public.profile') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Profil</a>
                <a href="{{ route('public.news') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Berita</a>
                <a href="{{ route('public.events') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Agenda</a>
                <a href="{{ route('public.gallery') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Galeri</a>
                <a href="{{ route('public.achievements') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Prestasi</a>
                <a href="{{ route('public.facilities') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Fasilitas</a>
                <a href="{{ route('public.contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Kontak</a>
                @if(Route::has('public.ppdb'))
                    <a href="{{ route('public.ppdb') }}" class="block px-3 py-2 rounded-md text-base font-bold text-blue-600 hover:bg-slate-50">Informasi PPDB</a>
                @endif
                <div class="border-t border-slate-100 my-2 pt-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-bold text-slate-900 bg-slate-100 hover:bg-slate-200 text-center">Login Portal Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-auto border-t border-slate-800 bg-slate-950 px-6 py-12 text-slate-400">
        <div class="mx-auto max-w-7xl grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    @if(isset($settings->logo_path) && $settings->logo_path)
                        <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-8 w-auto opacity-80">
                    @endif
                    <span class="text-xl font-bold text-white">{{ $settings->school_name ?? 'LavaSMSID' }}</span>
                </div>
                <p class="text-sm leading-relaxed max-w-md">{{ $settings->tagline ?? 'Sistem Manajemen Sekolah Modern khusus SMK.' }}</p>
                <div class="mt-6 flex gap-4">
                    @if(isset($settings->facebook_url) && $settings->facebook_url)
                        <a href="{{ $settings->facebook_url }}" target="_blank" class="hover:text-white transition-colors">Facebook</a>
                    @endif
                    @if(isset($settings->instagram_url) && $settings->instagram_url)
                        <a href="{{ $settings->instagram_url }}" target="_blank" class="hover:text-white transition-colors">Instagram</a>
                    @endif
                    @if(isset($settings->youtube_url) && $settings->youtube_url)
                        <a href="{{ $settings->youtube_url }}" target="_blank" class="hover:text-white transition-colors">YouTube</a>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Informasi</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.profile') }}" class="hover:text-white transition-colors">Profil Sekolah</a></li>
                    <li><a href="{{ route('public.departments') }}" class="hover:text-white transition-colors">Program Keahlian</a></li>
                    <li><a href="{{ route('public.achievements') }}" class="hover:text-white transition-colors">Prestasi</a></li>
                    <li><a href="{{ route('public.news') }}" class="hover:text-white transition-colors">Berita Terkini</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Kontak Kami</h3>
                <ul class="space-y-3 text-sm">
                    @if(isset($settings->school_address) && $settings->school_address)
                        <li class="flex items-start gap-2">
                            <span>📍</span> <span>{{ $settings->school_address }}</span>
                        </li>
                    @endif
                    @if(isset($settings->school_phone) && $settings->school_phone)
                        <li class="flex items-center gap-2">
                            <span>📞</span> <span>{{ $settings->school_phone }}</span>
                        </li>
                    @endif
                    @if(isset($settings->school_email) && $settings->school_email)
                        <li class="flex items-center gap-2">
                            <span>✉️</span> <span>{{ $settings->school_email }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="mx-auto max-w-7xl mt-12 pt-8 border-t border-slate-800 text-xs text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} {{ $settings->school_name ?? 'LavaSMSID' }}. Hak Cipta Dilindungi.</p>
            <p>Dibangun dengan <a href="#" class="text-blue-400 hover:text-blue-300">LavaSMSID</a></p>
        </div>
    </footer>
</body>
</html>
