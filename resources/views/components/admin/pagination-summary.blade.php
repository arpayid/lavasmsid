@props(['paginator'])

@if($paginator->total() > 0)
    <span class="text-sm text-slate-500">
        Menampilkan {{ $paginator->firstItem() }} s/d {{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
    </span>
@endif
