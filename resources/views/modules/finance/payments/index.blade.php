<x-admin-layout heading="Pembayaran">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2 flex-wrap">
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status')=='pending')>Pending</option>
                <option value="verified" @selected(request('status')=='verified')>Terverifikasi</option>
                <option value="rejected" @selected(request('status')=='rejected')>Ditolak</option>
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        <a href="{{ route('admin.finance.payments.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white">Catat Pembayaran</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b bg-slate-50"><tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">No. Kwitansi</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th>
                    <th class="hidden md:table-cell px-4 py-3 text-right font-semibold text-slate-700">Jumlah</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
                </tr></thead>
                <tbody class="divide-y">
                    @forelse($payments as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $p->receipt_number }}</td>
                        <td class="px-4 py-3 font-medium">{{ $p->invoice->student->name ?? '-' }}</td>
                        <td class="hidden md:table-cell px-4 py-3">{{ $p->paid_at->format('d M Y') }}</td>
                        <td class="hidden md:table-cell px-4 py-3 text-right">Rp{{ number_format($p->amount,0,',','.') }}</td>
                        <td class="px-4 py-3"><x-admin.badge :label="$p->status" :variant="$p->status=='verified'?'success':($p->status=='rejected'?'danger':'warning')" /></td>
                        <td class="px-4 py-3 text-right">
                            @if($p->status === 'pending' && auth()->user()->can('finance.verify'))
                            <form method="POST" action="{{ route('admin.finance.payments.verify', $p) }}" class="inline">
                                @csrf
                                <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white">Verifikasi</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-16"><x-admin.empty-state title="Belum ada pembayaran" message="Catat pembayaran siswa." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())<div class="px-4 py-3 border-t">{{ $payments->links() }}</div>@endif
    </div>
</x-admin-layout>