@props([
    'action' => '#',
    'label' => 'Hapus',
    'message' => 'Apakah Anda yakin ingin menghapus data ini?',
])

<div x-data="{ open: false }" class="inline">
    <button type="button"
            x-on:click="open = true"
            {{ $attributes->merge(['class' => 'rounded-lg p-1.5 text-red-500 hover:bg-red-50']) }}>
        {{ $label }}
    </button>

    <div x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
         x-on:click.self="open = false">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
            <p class="mt-2 text-sm text-slate-600">{{ $message }}</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        x-on:click="open = false"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <form method="POST" action="{{ $action }}" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
