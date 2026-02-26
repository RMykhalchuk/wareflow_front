@php
    $isDisabled = $disabled ? 'disabled' : '';
    $disabledStyle = $disabled ? 'style=color:var(--bs-btn-disabled-color);pointer-events:none;background-color:var(--bs-btn-disabled-bg);border-color:var(--bs-btn-disabled-border-color);opacity:var(--bs-btn-disabled-opacity);' : '';
@endphp

<div class="col-12 d-flex mb-1">
    <a id="{{ $id }}-img-btn" type="button" href="#" class="me-25">
        <img
            src="{{ $imageSrc }}"
            id="{{ $id }}-img"
            class="uploadedAvatar rounded me-50 object-fit-cover"
            alt="uploaded image"
            height="100"
            width="100"
        />
    </a>
    <div class="d-flex align-items-end mb-1 ms-1">
        <div>
            <label
                for="{{ $id }}"
                {!! $disabledStyle !!}
                class="btn btn-sm btn-primary mb-75 me-75 waves-effect waves-float waves-light"
            >
                {{ __('localization.components.avatar.upload') }}
            </label>
            <input
                type="file"
                id="{{ $id }}"
                name="{{ $name }}"
                hidden
                {{ $isDisabled }}
                accept="image/jpeg, image/png, image/gif"
            />
            <button
                type="submit"
                id="{{ $id }}-reset"
                class="btn btn-sm btn-outline-secondary mb-75 waves-effect"
                {{ $isDisabled }}
            >
                {{ __('localization.components.avatar.delete') }}
            </button>
            <p class="mb-0 text-secondary">
                {{ __('localization.components.avatar.format_jpg') }}
            </p>
            <p class="mb-0 text-secondary">
                {{ __('localization.components.avatar.size_limit') }}
            </p>
        </div>
    </div>
</div>
