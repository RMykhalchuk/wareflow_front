@props([
    'label',
    'value' => null,
    'labelClass' => 'col-6 col-md-3 f-15',
    'valueClass' => 'col-auto col-md-9 d-flex justify-content-between gap-1 f-15 fw-bold',
    'id' => 'copy-'.uniqid(),
    'copyButton' => true,
    //новийпропсдляпоказу/приховуваннякнопки,
])

@php
    $contentId = 'copy-content-' . $id;
    $buttonId = 'copy-button-' . $id;
@endphp

<div class="row mx-0 py-1 copy-wrapper position-relative">
    <div class="{{ $labelClass }}">
        {{ $label }}
    </div>

    <div class="{{ $valueClass }}" id="{{ $contentId }}">
        @if (! is_null($value))
            {{ $value }}
        @else
            {{ $slot }}
        @endif

        @if ($copyButton)
            <button
                id="{{ $buttonId }}"
                type="button"
                class="btn btn-link p-0 copy-btn"
                style="line-height: 1"
            >
                <i data-feather="copy"></i>
            </button>
        @endif
    </div>
</div>
