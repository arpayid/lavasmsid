<x-admin-layout heading="Detail Notifikasi">
    <div class="max-w-3xl mx-auto space-y-6 pb-12">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.communication.notifications.index') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
            @can('communication.delete')
            <form action="{{ route('admin.communication.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">Hapus</button>
            </form>
            @endcan
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="flex justify-between items-start mb-6 pb-6 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $notification->title }}</h2>
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-widest mt-1">{{ $notification->type }}</p>
                </div>
                <div class="text-right">
                    <div class="text-xs font-mono text-slate-400">{{ $notification->created_at->format('d M Y, H:i') }}</div>
                    <div class="mt-1">
                        <x-admin.badge :label="$notification->is_read ? 'Sudah Dibaca' : 'Baru'" :variant="$notification->is_read ? 'default' : 'primary'" size="xs" />
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-lg">
                {{ $notification->message }}
            </div>

            @if($notification->action_url)
            <div class="mt-8 pt-6 border-t border-slate-100">
                <a href="{{ $notification->action_url }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-lg shadow-indigo-100">
                    Buka Tautan Aksi ↗
                </a>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
