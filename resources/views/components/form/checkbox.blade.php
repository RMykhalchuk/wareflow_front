@props([
    'id',
    'name',
    'label' => '',
    'checked' => false,
    'inline' => false,
    'class' => 'col-12 col-md-6 mb-1',
])

@php
    $wrapperClass = $class;
    $checkClass = $inline ? 'form-check form-check-inline' : 'form-check';
@endphp

<div class="{{ $wrapperClass }}">
    <div class="{{ $checkClass }}">
        <input
            type="checkbox"
            class="form-check-input"
            id="{{ $id }}"
            name="{{ $name }}"
            value="1"
            {{ $checked ? 'checked' : '' }}
            {{ $attributes }}
        />
        <label class="form-check-label" for="{{ $id }}">
            {{ __($label) }}
        </label>
    </div>
</div>
