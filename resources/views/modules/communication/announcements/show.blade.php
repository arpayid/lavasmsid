<x-admin-layout heading="Detail Pengumuman">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.communication.announcements.index') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
            <div class="flex gap-2">
                @can('communication.update')
                <a href="{{ route('admin.communication.announcements.edit', $announcement) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">Edit Pengumuman</a>
                @endcan
            </div>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 leading-tight">{{ $announcement->title }}</h2>
                    <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                        <div class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ $announcement->creator->name ?? 'Admin' }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ $announcement->published_at?->format('d M Y H:i') ?? 'Belum terbit' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 items-end">
                    <x-admin.badge :label="$announcement->is_published ? 'Terbit' : 'Draft'" :variant="$announcement->is_published ? 'success' : 'default'" />
                    <x-admin.badge :label="ucfirst($announcement->priority)" :variant="$announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning' : 'default')" />
                </div>
            </div>

            <div class="border-t border-slate-100 pt-8">
                <div class="mb-4 flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Target Penerima:</span>
                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-600">{{ ucfirst($announcement->target) }}</span>
                </div>
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
                    {!! nl2br(e($announcement->content)) !!}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
