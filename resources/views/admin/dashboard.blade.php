<x-admin-layout heading="Dashboard">
    {{-- Stats --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        @foreach($stats as $label => $value)
            <x-admin.stat-card :label="str_replace('_', ' ', $label)" :value="$value" />
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 xl:col-span-2">
            <h2 class="font-bold">Grafik Siswa per Jurusan</h2>
            <canvas id="studentsChart" class="mt-6"></canvas>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="font-bold">Quick Action</h2>
            <div class="mt-4 space-y-3 text-sm">
                <a class="block rounded-xl bg-indigo-50 px-4 py-3 text-indigo-700" href="{{ route('admin.students.index') }}">Kelola Siswa</a>
                <a class="block rounded-xl bg-emerald-50 px-4 py-3 text-emerald-700" href="{{ route('admin.departments.index') }}">Kelola Jurusan</a>
            </div>
        </div>
    </div>
</x-admin-layout>
