{{-- Breadcrumb: horizontal list with divider --}}
@if(isset($breadcrumb) && $breadcrumb)
<nav class="mb-4 flex items-center space-x-1 text-sm text-slate-500" aria-label="breadcrumb">
    <ol class="inline-flex items-center space-x-1">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">Dashboard</a>
        </li>
        @if(is_string($breadcrumb))
        <li>
            <span class="mx-1">/</span>
            <span>{{ $breadcrumb }}</span>
        </li>
        @elseif(is_array($breadcrumb))
        @foreach($breadcrumb as $index => $bc)
        <li>
            <span class="mx-1">/</span>
            <span>{{ $bc }}</span>
        </li>
        @endforeach
        @endif
    </ol>
</nav>
@endif
