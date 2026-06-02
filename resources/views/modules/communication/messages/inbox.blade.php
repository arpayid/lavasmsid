<x-admin-layout heading="Kotak Masuk (Inbox)">
    <div class="mb-6 flex justify-between items-center">
        <div class="flex gap-4">
            <a href="{{ route('admin.communication.messages.inbox') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-md">Inbox</a>
            <a href="{{ route('admin.communication.messages.outbox') }}" class="rounded-lg bg-white border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Outbox</a>
        </div>
        @can('communication.create')
        <a href="{{ route('admin.communication.messages.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-indigo-700">+ Kirim Pesan</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="border-b bg-slate-50 text-slate-700 font-semibold">
                <tr>
                    <th class="px-6 py-4">Pengirim</th>
                    <th class="px-6 py-4">Subjek</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($messages as $msg)
                <tr @class(['hover:bg-slate-50 transition-colors', 'bg-indigo-50/30' => !$msg->is_read])>
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $msg->sender->name }}</td>
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $msg->subject }}</td>
                    <td class="px-6 py-4 font-mono text-xs">{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-center">
                        <x-admin.badge :label="$msg->is_read ? 'Dibaca' : 'Baru'" :variant="$msg->is_read ? 'default' : 'primary'" size="xs" />
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <a href="{{ route('admin.communication.messages.show', $msg) }}" class="text-indigo-600 hover:underline font-bold">Baca</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-16 text-center text-slate-500 italic">Belum ada pesan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($messages->hasPages())<div class="px-6 py-4 border-t">{{ $messages->links() }}</div>@endif
    </div>
</x-admin-layout>
