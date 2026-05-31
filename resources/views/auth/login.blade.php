<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login LavaSMSID</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="min-h-screen bg-slate-950 text-white">
<div class="grid min-h-screen lg:grid-cols-2">
    <section class="hidden bg-gradient-to-br from-indigo-700 to-slate-950 p-12 lg:flex lg:flex-col lg:justify-between">
        <div class="text-2xl font-black">LavaSMSID</div>
        <div><h1 class="text-5xl font-black leading-tight">Admin Panel Profesional untuk SMK.</h1><p class="mt-5 text-blue-100">Akademik, PPDB, keuangan, PKL, BKK, alumni, dan laporan terpadu.</p></div>
    </section>
    <section class="flex items-center justify-center p-6">
        <form method="POST" action="{{ route('login') }}" class="w-full max-w-md rounded-3xl bg-white p-8 text-slate-900 shadow-2xl">
            @csrf
            <h2 class="text-2xl font-black">Masuk Portal</h2>
            <p class="mt-1 text-sm text-slate-500">Gunakan akun yang dibuat dari seeder.</p>
            @error('email')<div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror
            <label class="mt-6 block text-sm font-semibold">Email</label><input name="email" type="email" value="{{ old('email', 'admin@lavasmsid.local') }}" class="mt-2 w-full rounded-xl border-slate-300" required autofocus>
            <label class="mt-4 block text-sm font-semibold">Password</label><input name="password" type="password" class="mt-2 w-full rounded-xl border-slate-300" required>
            <button class="mt-6 w-full rounded-xl bg-indigo-600 px-4 py-3 font-bold text-white">Login</button>
            <p class="mt-4 text-xs text-slate-500">Default: admin@lavasmsid.local / password</p>
        </form>
    </section>
</div>
</body>
</html>
