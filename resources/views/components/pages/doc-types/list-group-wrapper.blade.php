{{-- resources/views/components/action-list-wrapper.blade.php --}}
{{-- // висота контейнера, можна змінити при виклику --}}

@props([
    'height' => '400px',
])

<div class="pe-2 document-new-fields" style="height: {{ $height }}; overflow-y: auto">
    <ul class="list-group" style="list-style-type: none; padding: 0">
        {{ $slot }}
    </ul>
</div>
