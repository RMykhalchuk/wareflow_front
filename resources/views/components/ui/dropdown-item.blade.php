@props([
    'href' => '#',
    'text',
    'class' => 'dropdown-item d-flex align-items-center gap-1',
    'icon' => null,
    'iconStyle' => 'width: 16px; height: 16px;',
])

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => $class]) }}
    {{ $attributes }}
>
    @if ($icon)
        <i data-feather="{{ $icon }}" style="{{ $iconStyle }}"></i>
    @endif

    {{ $text }}
</a>
