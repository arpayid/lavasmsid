<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin LavaSMSID' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900 antialiased">
<div class="min-h-screen lg:flex">
    <aside class="bg-slate-950 text-white lg:w-72 p-5">
        <div class="text-xl font-bold tracking-tight">LavaSMSID</div>
        <p class="mt-1 text-xs text-slate-400">SMK Management System</p>
        <nav class="mt-8 space-y-1 text-sm">
            <a class="block rounded-xl px-4 py-3 bg-indigo-600" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.students.index') }}">Siswa</a>
            <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.teachers.index') }}">Guru</a>
            <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.departments.index') }}">Jurusan</a>
        </nav>
    </aside>
    <main class="flex-1">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <div><p class="text-xs uppercase text-slate-500">Admin Panel</p><h1 class="text-lg font-semibold">{{ $heading ?? 'Dashboard' }}</h1></div>
            <form method="POST" action="{{ route('logout') }}">@csrf <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm text-white">Logout</button></form>
        </header>
        <section class="p-6">{{ $slot }}</section>
    </main>
</div>
</body>
</html>
