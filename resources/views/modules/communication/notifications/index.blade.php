<x-admin-layout heading="Pusat Notifikasi">
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Semua Notifikasi Anda</h3>
        <div class="flex gap-2">
            @can('communication.update')
            <form action="{{ route('admin.communication.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-white border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">Tandai Semua Terbaca</button>
            </form>
            @endcan
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notification)
            <div @class(['p-6 hover:bg-slate-50 transition-colors flex gap-4', 'bg-blue-50/30' => !$notification->is_read])>
                <div @class([
                    'h-10 w-10 rounded-full shrink-0 flex items-center justify-center font-bold',
                    'bg-blue-100 text-blue-600' => !$notification->is_read,
                    'bg-slate-100 text-slate-400' => $notification->is_read
                ])>
                    @if($notification->type === 'announcement') 📣 @elseif($notification->type === 'payment') 💰 @else 🔔 @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 @class(['text-sm font-bold', 'text-blue-900' => !$notification->is_read, 'text-slate-900' => $notification->is_read])>
                                {{ $notification->title }}
                            </h4>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-tighter mt-0.5">{{ $notification->type }}</p>
                        </div>
                        <span class="text-xs font-mono text-slate-400 shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $notification->message }}</p>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('admin.communication.notifications.show', $notification) }}" class="text-xs font-bold text-indigo-600 hover:underline">Detail</a>
                        @if(!$notification->is_read && auth()->user()->can('communication.update'))
                        <form action="{{ route('admin.communication.notifications.mark-read', $notification) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-slate-500 hover:text-slate-800">Tandai Terbaca</button>
                        </form>
                        @endif
                        @if($notification->action_url)
                        <a href="{{ $notification->action_url }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Aksi ↗</a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-slate-500 italic text-sm">Belum ada notifikasi untuk Anda.</div>
            @endforelse
        </div>
        @if($notifications->hasPages())<div class="px-6 py-4 border-t bg-slate-50/50">{{ $notifications->links() }}</div>@endif
    </div>
</x-admin-layout>
