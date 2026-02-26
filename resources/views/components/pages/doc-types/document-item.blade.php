@props([
    'id',
    'name',
    'draggable' => true,
])

<li
    class="{{ $draggable ? 'group ui-draggable ui-draggable-handle' : 'group sortable-item-document' }}"
    data-id="{{ $id }}"
>
    <div class="accordion-header ui-accordion-header mb-0 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center justify-content-start">
                <p class="system-title translate-system-document-type m-0 text-center">
                    {{ $name }}
                </p>
            </div>

            <div
                class="d-flex {{ $draggable ? '' : 'd-none' }}"
                id="header-badge{{ $draggable ? -$id : '' }}"
            >
                <div class="removeButton" data-delete-action="{{ $id }}">
                    <img
                        src="{{ asset('assets/icons/entity/document-type/close-field-base.svg') }}"
                        alt="close-field-base"
                    />
                </div>
            </div>
        </div>
    </div>
</li>
