<x-admin-layout heading="Tagihan Siswa">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2 flex-wrap">
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="unpaid" @selected(request('status')=='unpaid')>Belum Lunas</option>
                <option value="partial" @selected(request('status')=='partial')>Sebagian</option>
                <option value="paid" @selected(request('status')=='paid')>Lunas</option>
            </select>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        <a href="{{ route('admin.finance.invoices.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white">+ Buat Tagihan</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b bg-slate-50"><tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">No. Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Siswa</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Kategori</th>
                    <th class="hidden md:table-cell px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Tagihan</th>
                    <th class="hidden md:table-cell px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Terbayar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                </tr></thead>
                <tbody class="divide-y">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $inv->invoice_number }}</td>
                        <td class="px-4 py-3 font-medium">{{ $inv->student->name ?? '-' }}</td>
                        <td class="hidden md:table-cell px-4 py-3 text-slate-600">{{ $inv->paymentCategory->name ?? '-' }}</td>
                        <td class="hidden md:table-cell px-4 py-3 text-right">Rp{{ number_format($inv->amount,0,',','.') }}</td>
                        <td class="hidden md:table-cell px-4 py-3 text-right">Rp{{ number_format($inv->paid_amount,0,',','.') }}</td>
                        <td class="px-4 py-3">
                            @php $v = $inv->status === 'paid' ? 'success' : ($inv->status === 'partial' ? 'warning' : 'danger'); $l = $inv->status === 'paid' ? 'Lunas' : ($inv->status === 'partial' ? 'Sebagian' : 'Belum Lunas'); @endphp
                            <x-admin.badge :label="$l" :variant="$v" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.finance.invoices.show', $inv) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-16"><x-admin.empty-state title="Belum ada tagihan" message="Buat tagihan siswa." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())<div class="px-4 py-3 border-t">{{ $invoices->links() }}</div>@endif
    </div>
</x-admin-layout>