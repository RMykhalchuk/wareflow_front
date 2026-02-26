@props([
    'id',
    'titleKey',
    'descriptionKey',
    'iconName',
    'altText',
])

<li class="field-type border-bottom py-1 list-unstyled" id="{{ $id }}">
    <a class="px-1 d-flex gap-1 align-items-center">
        <div class="flex-grow-1 d-flex flex-column justify-content-center gap-lg-25">
            <h5 class="fw-bolder mb-0">
                {{ __($titleKey) }}
            </h5>
            <p class="text-secondary mb-0">
                {{ __($descriptionKey) }}
            </p>
        </div>
        <img
            src="{{ asset('assets/icons/entity/document-type/' . $iconName) }}"
            alt="{{ $altText }}"
        />
    </a>
</li>
