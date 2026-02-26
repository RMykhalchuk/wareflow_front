@props([
    'id',
    'name',
    'label' => null,
    'placeholder' => '',
    'class' => 'col-12 col-md-6 mb-1 position-relative',
    'required' => false,
    'dateTime' => false,
    'pickerClass' => null,
])

@php
    $wrapperClass = $class;
    $requiredAttr = $required ? 'required' : '';

    // Якщо pickerClass передано — використовуємо його
    // Якщо ні — ставимо дефолт залежно від dateTime
    $pickerClass = $pickerClass ?? ($dateTime ? 'flatpickr-date-time' : 'flatpickr-basic');
@endphp

<div class="{{ $wrapperClass }}">
    @isset($label)
        <label class="form-label" for="{{ $id }}">
            {{ __($label) }}
        </label>
    @endisset

    <input
        type="text"
        id="{{ $id }}"
        name="{{ $name }}"
        class="form-control {{ $pickerClass }} flatpickr-input"
        placeholder="{{ __($placeholder) }}"
        {{ $requiredAttr }}
        readonly="readonly"
        {{ $attributes }}
    />

    <span
        class="cursor-pointer text-secondary position-absolute top-50"
        style="right: 27px; pointer-events: none"
    >
        <i data-feather="calendar"></i>
    </span>
</div>
