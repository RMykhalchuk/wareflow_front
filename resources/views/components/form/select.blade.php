@php
    $wrapperClass = $class ?? 'col-12 col-md-6 mb-1';
    $selectClasses = 'select2 form-select';
    if (isset($attributes['multiple']) && $attributes['multiple']) {
        $selectClasses .= ' default-height-fix';
    }
@endphp

<div class="{{ $wrapperClass }}">
    <label class="form-label" for="{{ $id }}">
        {{ __($label) }}
    </label>
    <select
        class="{{ $selectClasses }}"
        name="{{ $name }}"
        id="{{ $id }}"
        data-placeholder="{{ __($placeholder) }}"
        {{ $attributes }}
    >
        <option value=""></option>

        @isset($slot)
            {{ $slot }}
        @endisset
    </select>
</div>
