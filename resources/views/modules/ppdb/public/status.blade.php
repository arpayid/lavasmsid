<x-public-layout title="Status Pendaftaran">
    <div class="mx-auto max-w-2xl py-12">
        <div class="rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200 text-center">
            <h1 class="text-2xl font-bold text-slate-900 mb-4">Status Pendaftaran</h1>

            <div class="mb-6">
                <p class="text-sm text-slate-500">No. Pendaftaran</p>
                <p class="text-2xl font-mono font-bold text-indigo-700">{{ $registration->registration_number }}</p>
            </div>

            <div class="mb-6">
                <p class="text-sm text-slate-500">Nama</p>
                <p class="text-lg font-semibold">{{ $registration->candidate_name }}</p>
            </div>

            @php
                $icons = [
                    'submitted' => ['bg-amber-100 text-amber-700', 'Pending'],
                    'verified' => ['bg-blue-100 text-blue-700', 'Terverifikasi'],
                    'accepted' => ['bg-emerald-100 text-emerald-700', 'Diterima'],
                    'rejected' => ['bg-red-100 text-red-700', 'Ditolak'],
                ];
                $st = $icons[$registration->status] ?? ['bg-slate-100 text-slate-700', $registration->status];
            @endphp

            <div class="mb-8">
                <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full {{ $st[0] }}">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($registration->status === 'accepted')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @elseif($registration->status === 'rejected')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @endif
                    </svg>
                    <span class="text-lg font-bold">{{ $st[1] }}</span>
                </div>
            </div>

            <a href="{{ route('public.ppdb.form') }}" class="inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white">Daftar Lagi</a>
        </div>
    </div>
</x-public-layout>