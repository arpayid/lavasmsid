<x-admin-layout heading="Kelola Halaman Website">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @php $slugs = ['about'=>'Tentang Sekolah','vision'=>'Visi','mission'=>'Misi','history'=>'Sejarah','welcome'=>'Sambutan']; @endphp
        @foreach($slugs as $slug => $title)
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-semibold text-slate-900">{{ $title }}</h3>
            <p class="text-xs text-slate-500 mt-1">/{{ $slug }}</p>
            <a href="{{ route('admin.website.pages.edit', $slug) }}" class="mt-3 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Edit</a>
        </div>
        @endforeach
    </div>
</x-admin-layout>
