@props([
    'id' => null,
    'title' => '',
    'icon' => null,
    'class' => 'btn p-25 h-50',
    'iconStyle' => 'cursor: pointer; transform: scale(1.2);',
    'type' => 'button',
])

<button
    type="{{ $type }}"
    id="{{ $id }}"
    title="{{ $title }}"
    {{ $attributes->merge(['class' => $class]) }}
>
    @if ($icon)
        <i data-feather="{{ $icon }}" style="{{ $iconStyle }}"></i>
    @endif

    {{-- Текстовий слот — якщо передано --}}
    @if (trim($slot))
        <span class="ms-50">{{ $slot }}</span>
    @endif
</button>
