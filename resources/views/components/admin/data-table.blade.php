{{-- Data table: responsive, searchable, paginated, with actions --}}
<div class="relative">
    {{-- Search --}}
    @if($showSearch ?? true)
    <div class="mb-4">
        <div class="relative">
            <input type="search"
                   x-model="search"
                   @input.debounce.300ms="$dispatch('filter', search)"
                   placeholder="{{ $placeholder ?? 'Cari...' }}"
                   class="block w-full rounded-md border border-slate-300 bg-white py-2 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M14 10h4.7a2 2 0 002-2 2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 002 2 2 2 0 012 2v4a2 2 0 01-2 2v-.5z"/></svg>
        </div>
    </div>
    @endif

    {{-- Empty state --}}
    @if(count($items) === 0)
    <x-empty-state :icon="$icon ?? '<svg class=\"h-6 w-6 text-slate-400\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 11l-2-2 9 9 3-4-6-3 2-2 4-9-3 4 9-9z\"></svg>'"
                   :title="$emptyTitle ?? 'Belum ada data'"
                   :message="$emptyMessage ?? 'Klik tombol di atas untuk menambahkan data baru.'"
                   :actionText="$actionText ?? 'Tambah Baru'"
                   :actionUrl="$actionUrl ?? ''" />
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wiser">
                        {{ $header }}
                    </th>
                    @endforeach
                    @if($showActions ?? true)
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wiser">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($items as $item)
                <tr>
                    @foreach($rows as $key => $row)
                    @if(is_callable($row))
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $row($item) }}</td>
                    @elseif(is_array($row) && isset($row[0], $row[1]))
                        {{-- Array format: [column, callback] --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $row[1]($item) }}</td>
                    @else
                        {{-- String format: column name --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $item->$row ?? '' }}</td>
                    @endif
                    @endforeach
                    @if($showActions ?? true)
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        {{ $actions($item) ?? '' }}
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Pagination --}}
        @if($items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $items->hasPages())
        <div class="mt-4 flex items-center justify-between px-4">
            <x-admin.pagination-summary :paginator="$items" />
            {{ $items->links() }}
        </div>
        @endif
    @endif
</div>
