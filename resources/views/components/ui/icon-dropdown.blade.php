@props([
    'id' => 'iconDropdown',
    'icon' => 'more-horizontal',
    'iconStyle' => 'cursor: pointer;',
    'menuClass' => '',
    'text' => null,
    //новийпропсдлятексту,
])

<div class="btn-group align-items-center">
    <div id="{{ $id }}" style="{{ $iconStyle }}" data-bs-toggle="dropdown">
        @if ($text)
            <span class="me-50 fw-bold">{{ $text }}</span>
            {{-- текст зліва з невеликим відступом справа --}}
        @endif

        <i data-feather="{{ $icon }}" aria-expanded="false" style="transform: scale(1.2)"></i>
    </div>

    <div class="dropdown-menu {{ $menuClass }}" aria-labelledby="{{ $id }}">
        {{ $slot }}
    </div>
</div>
