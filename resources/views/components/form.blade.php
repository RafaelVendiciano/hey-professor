@props([
    'action',
    'method'
])

<form action="{{ $action }}" method="{{ $method }}">
    @csrf

    {{ $slot }}
</form>