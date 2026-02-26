@props([
    'class' => 'col-12 p-0 my-2 card-data',
])

{{-- card-data-reverse-darker --}}

<div {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</div>
