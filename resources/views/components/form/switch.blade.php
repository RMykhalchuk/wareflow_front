@php
    $wrapperClass = $class ?? 'col-12 col-md-6 mb-1';
@endphp

<div class="{{ $wrapperClass }}">
    <div class="form-check form-switch mt-1">
        <label class="form-label mt-25" for="{{ $id }}">
            {{ __($label) }}
        </label>
        <input
            type="checkbox"
            class="form-check-input"
            id="{{ $id }}"
            name="{{ $name }}"
            {{ $checked ? 'checked' : '' }}
            {{ $attributes }}
        />
    </div>
</div>
