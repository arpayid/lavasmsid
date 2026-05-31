<x-public-layout title="Cek Status PPDB">
    <div class="mx-auto max-w-xl py-12">
        <div class="rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
            <h1 class="text-2xl font-bold text-slate-900 text-center mb-6">Cek Status Pendaftaran</h1>

            <form method="GET" action="{{ route('public.ppdb.check') }}" class="flex gap-2">
                <input type="text" name="registration_number" value="{{ request('registration_number') }}" placeholder="Masukkan No. Pendaftaran" class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white">Cari</button>
            </form>

            @if($registration)
            <div class="mt-6 p-6 rounded-xl bg-slate-50">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">No. Pendaftaran</dt><dd class="font-mono font-semibold">{{ $registration->registration_number }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Nama</dt><dd class="font-semibold">{{ $registration->candidate_name }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Jurusan</dt><dd>{{ $registration->department->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Status</dt>
                        <dd>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                @if($registration->status=='accepted') bg-emerald-100 text-emerald-700
                                @elseif($registration->status=='rejected') bg-red-100 text-red-700
                                @elseif($registration->status=='verified') bg-blue-100 text-blue-700
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ $registration->status }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
            @elseif(request('registration_number'))
            <div class="mt-6 p-6 rounded-xl bg-amber-50 text-center text-sm text-amber-700">
                Pendaftar dengan nomor "{{ request('registration_number') }}" tidak ditemukan.
            </div>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ route('public.ppdb.form') }}" class="text-sm text-indigo-600 hover:underline">Belum punya akun? Daftar di sini</a>
            </div>
        </div>
    </div>
</x-public-layout>