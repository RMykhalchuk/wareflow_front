@props([
    'target',
    'id' => null,
    'type' => 'button',
    'class' => 'btn btn-secondary',
    'text' => null,
    'icon' => null,
    'iconStyle' => 'margin-right: 0.5rem;',
])

<button
    {{
        $attributes->merge([
            'type' => $type,
            'data-bs-toggle' => 'modal',
            'data-bs-target' => "#$target",
            'id' => $id,
            'class' => $class,
        ])
    }}
>
    {{-- Іконка (якщо вказано) --}}
    @if ($icon)
        <i data-feather="{{ $icon }}" style="{{ $iconStyle }}"></i>
    @endif

    {{-- Текст (якщо вказано) --}}
    @if ($text)
        {{ $text }}
    @endif

    {{-- Слот (якщо щось додано вручну) --}}
    {{ $slot }}
</button>
