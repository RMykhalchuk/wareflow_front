@props([
    'id',
    'label',
    'placeholder' => '',
    'class' => 'col-12 col-md-6 mb-1',
    'tags' => [],
    'name' => null,
    'unit' => null,
    'type' => 'text',
])

@php
    $getLocalizedUnit = function ($unit, $count = 1) {
        if (! $unit) {
            return '';
        }
        $key = "localization.sku_create_{$unit}_unit";
        return trans_choice($key, $count);
    };

    // 👇 Декодуємо JSON, якщо передано рядком
    $tags = is_string($tags) ? json_decode($tags, true) : $tags;
@endphp

<div class="{{ $class }}">
    <label class="form-label" for="{{ $id }}">
        {{ __($label) }}
    </label>

    <div class="tag-input-wrapper" id="tag-container-{{ $id }}" data-unit="{{ $unit }}">
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            class="tag-input"
            placeholder="{{ __($placeholder) }}"
            {{ $attributes }}
        />

        @if ($name)
            <input
                type="hidden"
                name="{{ $name }}"
                id="hidden-{{ $id }}"
                value="{{ json_encode($tags) }}"
            />
        @endif
    </div>
</div>
