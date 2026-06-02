<x-admin-layout heading="Komunikasi & Notifikasi">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Announcements Card --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Pengumuman</h3>
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-indigo-100 text-indigo-600">📣</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_announcements'] }}</span>
                <span class="text-xs text-slate-400">Total</span>
            </div>
            <div class="mt-4 flex gap-4">
                <div class="text-xs font-medium text-emerald-600">{{ $stats['published_announcements'] }} Terbit</div>
                <div class="text-xs font-medium text-slate-400">{{ $stats['draft_announcements'] }} Draft</div>
            </div>
        </div>

        {{-- Notifications Card --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Notifikasi</h3>
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-100 text-blue-600">🔔</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_notifications'] }}</span>
                <span class="text-xs text-slate-400">Total Terkirim</span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-medium text-blue-600">{{ $stats['unread_notifications'] }} Belum Dibaca</span>
            </div>
        </div>

        {{-- Messages Card --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Pesan Internal</h3>
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-purple-100 text-purple-600">✉️</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_messages'] }}</span>
                <span class="text-xs text-slate-400">Pesan Tersimpan</span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-medium text-purple-600">{{ $stats['unread_messages'] }} Belum Dibaca</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Announcements --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Pengumuman Terbaru</h3>
                <a href="{{ route('admin.communication.announcements.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentAnnouncements as $announcement)
                <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="text-sm font-bold text-slate-900">{{ $announcement->title }}</h4>
                        <x-admin.badge :label="ucfirst($announcement->priority)" :variant="$announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning' : 'default')" size="xs" />
                    </div>
                    <div class="flex items-center gap-3 text-[10px] text-slate-400 uppercase tracking-widest">
                        <span>{{ $announcement->published_at?->diffForHumans() ?? 'Draft' }}</span>
                        <span>&bull;</span>
                        <span>Target: {{ ucfirst($announcement->target) }}</span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-500 italic text-sm">Belum ada pengumuman.</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Notifications --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Notifikasi Terakhir</h3>
                <a href="{{ route('admin.communication.notifications.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentNotifications as $notification)
                <div class="px-6 py-4 flex gap-4 hover:bg-slate-50 transition-colors">
                    <div @class([
                        'h-2 w-2 rounded-full mt-1.5 shrink-0',
                        'bg-blue-500' => !$notification->is_read,
                        'bg-slate-200' => $notification->is_read
                    ])></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-900 font-medium truncate">{{ $notification->title }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $notification->message }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-500 italic text-sm">Belum ada notifikasi.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
