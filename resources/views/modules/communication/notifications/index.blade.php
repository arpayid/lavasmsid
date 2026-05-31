<x-admin-layout heading="Notifikasi Saya">
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500">Semua notifikasi Anda</p>
        <form method="POST" action="{{ route('admin.communication.notifications.read-all') }}" id="readAllForm">
            @csrf
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Tandai Semua Sudah Dibaca</button>
        </form>
    </div>
    <div class="space-y-3">
        @forelse($notifications as $n)
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 {{ $n->is_read ? '' : 'border-l-4 border-indigo-600' }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="font-medium text-slate-900">{{ $n->title }}</h3>
                    <p class="mt-1 text-sm text-slate-600">{{ $n->message }}</p>
                    <p class="mt-2 text-xs text-slate-400">{{ $n->created_at->diffForHumans() }} • {{ $n->type }}</p>
                </div>
                @if(!$n->is_read)
                <form method="POST" action="{{ route('admin.communication.notifications.read', $n) }}" class="ml-4">
                    @csrf
                    <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50">Tandai Dibaca</button>
                </form>
                @else
                <x-admin.badge label="Dibaca" variant="default" />
                @endif
            </div>
        </div>
        @empty
        <div class="rounded-xl bg-white p-16 text-center shadow-sm ring-1 ring-slate-200">
            <x-admin.empty-state title="Tidak ada notifikasi" message="Anda belum memiliki notifikasi." />
        </div>
        @endforelse
    </div>
    @if($notifications->hasPages())<div class="mt-4">{{ $notifications->links() }}</div>@endif
</x-admin-layout>
