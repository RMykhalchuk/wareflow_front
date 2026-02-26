@props([
    'id',
])

<div class="additional-settings px-50" id="{{ $id }}" style="min-height: 350px">
    {{ $slot }}
</div>
