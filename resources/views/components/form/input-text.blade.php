@props([
    'id',
    'name',
    'label',
    'placeholder' => null,
    'type' => 'text',
    'class' => null,
])

@php
    $wrapperClass = $class ?? 'col-12 col-md-6 mb-1';
@endphp

<div class="{{ $wrapperClass }}">
    <label class="form-label" for="{{ $id }}">
        {!! __($label) !!}
    </label>
    <input
        type="{{ $type }}"
        class="form-control"
        id="{{ $id }}"
        name="{{ $name }}"
        @isset($placeholder) placeholder="{{ __($placeholder) }}" @endisset
        {{ $attributes }}
    />
</div>
