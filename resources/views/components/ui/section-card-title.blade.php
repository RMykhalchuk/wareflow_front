@props([
    'level' => 5,
    'class' => 'fw-bolder',
])

@php
    $tag = 'h' . $level;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</{{ $tag }}>
