@php
    $wrapperClass = $class ?? 'col-12 col-md-6 mb-1';
@endphp

<div class="{{ $wrapperClass }}">
    @isset($label)
        <label class="form-label" for="{{ $id }}">
            {{ __($label) }}
        </label>
    @endisset

    <textarea
        class="form-control"
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows ?? 3 }}"
        placeholder="{{ __($placeholder) }}"
        {{ $attributes }}
    >
{{ $slot }}</textarea
    >
</div>
