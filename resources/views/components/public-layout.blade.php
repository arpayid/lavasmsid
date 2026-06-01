<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LavaSMSID' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased">
<nav class="sticky top-0 z-40 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
        <a href="{{ route('public.home') }}" class="text-xl font-black text-indigo-700">LavaSMSID</a>
        <div class="hidden gap-6 text-sm font-medium md:flex">
            <a href="{{ route('public.profile') }}">Profil</a>
            <a href="{{ route('ppdb.index') }}">PPDB</a>
            <a href="{{ route('public.contact') }}">Kontak</a>
        </div>
        <a href="{{ route('login') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Login Portal</a>
    </div>
</nav>
{{ $slot }}
<footer class="border-t border-slate-200 bg-slate-950 px-6 py-10 text-slate-300">
    <div class="mx-auto max-w-7xl"><b>LavaSMSID</b><p class="mt-2 text-sm">School Management System khusus SMK.</p></div>
</footer>
</body>
</html>
