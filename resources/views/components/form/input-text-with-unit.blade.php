@php
    $wrapperClass = $class ?? 'col-12 col-md-6 mb-1';
@endphp

<div id="{{ $id }}_wrapper" class="{{ $wrapperClass }}">
    @isset($label)
        <label class="form-label" for="{{ $id }}">
            {{ __($label) }}
        </label>
    @endisset

    <div class="input-group">
        <input
            type="text"
            class="form-control"
            name="{{ $name }}"
            id="{{ $id }}"
            placeholder="{{ __($placeholder) }}"
            {{ $attributes }}
        />
        <span class="input-group-text">
            @if ($isUnitHtml)
                {!! __($unit) !!}
            @else
                {{ __($unit) }}
            @endif
        </span>
    </div>
</div>
