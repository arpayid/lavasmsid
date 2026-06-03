@props(['method' => 'POST', 'action' => '', 'enctype' => null])

<form method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
      action="{{ $action }}"
      @if($enctype) enctype="{{ $enctype }}" @endif
      x-data="{ submitting: false }"
      x-on:submit="submitting = true; $nextTick(() => { $el.querySelectorAll('[type=submit]').forEach(b => b.disabled = true) })"
      {{ $attributes }}>
    @csrf
    @if(!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif
    {{ $slot }}
</form>
