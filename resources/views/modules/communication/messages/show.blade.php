<x-admin-layout heading="Baca Pesan Internal">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                <a href="{{ route('admin.communication.messages.inbox') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
            <div class="flex gap-2">
                @if($message->recipient_id === auth()->id())
                <form action="{{ route('admin.communication.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini dari inbox Anda?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">Hapus Pesan</button>
                </form>
                @endif
            </div>
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 leading-tight">{{ $message->subject }}</h2>
                        <div class="mt-4 flex flex-wrap items-center gap-6 text-sm text-slate-500">
                            <div class="flex items-center gap-2">
                                <span class="text-xs uppercase tracking-widest font-bold text-slate-400">Dari:</span>
                                <span class="font-bold text-slate-900">{{ $message->sender->name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs uppercase tracking-widest font-bold text-slate-400">Kepada:</span>
                                <span class="font-bold text-slate-900">{{ $message->recipient->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-mono text-slate-400">{{ $message->created_at->format('d M Y, H:i') }}</div>
                        @if($message->is_read)
                        <div class="mt-1 text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">Dibaca {{ $message->read_at?->diffForHumans() }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-8 py-10">
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-lg">
                    {!! nl2br(e($message->body)) !!}
                </div>
            </div>

            @if($message->recipient_id === auth()->id())
            <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-end">
                <a href="{{ route('admin.communication.messages.create', ['reply_to' => $message->sender_id, 'subject' => 'Re: '.$message->subject]) }}" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-md">
                    Balas Pesan
                </a>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
