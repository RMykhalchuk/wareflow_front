@props([
    'href',
    'title' => '',
    'icon' => 'edit',
    'class' => 'btn p-25 h-50',
    'linkClass' => 'text-secondary',
    'iconStyle' => 'cursor: pointer; transform: scale(1.2);',
    'type' => 'button',
])

<button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>
    <a href="{{ $href }}" title="{{ $title }}" class="{{ $linkClass }}">
        <i data-feather="{{ $icon }}" style="{{ $iconStyle }}"></i>
    </a>
</button>
