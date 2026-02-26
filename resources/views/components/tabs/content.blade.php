@props([
    'id',
    'labelledby' => null,
    'active' => false,
])

<div
    class="tab-pane fade {{ $active ? 'show active' : '' }}"
    id="{{ $id }}"
    aria-labelledby="{{ $labelledby ?? $id . '-tab' }}"
    role="tabpanel"
>
    {{ $slot }}
</div>
