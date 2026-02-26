@props([
    'id',
    'title',
    'active' => false,
    'disabled' => false,
])

{{--
    id     — ID контенту вкладки
    title  — Текст вкладки
    active — Чи активна вкладка
--}}

<li class="nav-item" role="presentation">
    <a
        class="nav-link {{ $active ? 'active' : '' }} {{ $disabled ? 'text-muted' : '' }}"
        {{ $disabled ? 'disabled' : '' }}
        id="{{ $id }}-tab"
        data-bs-toggle="tab"
        href="#{{ $id }}"
        aria-controls="{{ $id }}"
        role="tab"
        aria-selected="{{ $active ? 'true' : 'false' }}"
    >
        {{ $title }}
    </a>
</li>
