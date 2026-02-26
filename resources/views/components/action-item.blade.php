{{-- resources/views/components/action-item.blade.php --}}
{{-- // ключ дії, наприклад 'edit', 'delete', 'copy' --}}
{{-- // масив ролей ['admin' => true, 'driver' => false] --}}
{{-- // true -> ui-draggable, false -> sortable-item-action --}}

@props([
    'key',
    'roles' => [],
    'draggable' => true,
])

@php
    // Генеруємо іконку та заголовок по ключу
    $icon = match ($key) {
        'edit' => 'action/edit.svg',
        'delete' => 'action/trash.svg',
        'copy' => 'action/copy.svg',
        'carrying_out' => 'action/star.svg',
        'action' => 'action/star.svg',
        'print' => 'action/printer.svg',

        default => 'action/star.svg',
    };

    $title = __('localization.document_types_create_action_item_acordion_' . $key . '_title');

    // Клас для li
    $liClass = $draggable ? 'group ui-draggable ui-draggable-handle' : 'group sortable-item-action';
@endphp

<li class="{{ $liClass }}" data-system="{{ $key }}">
    <div class="accordion-header ui-accordion-header mb-0 bg-white" data-type="action">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img
                    class="pe-1"
                    src="{{ asset('assets/icons/entity/document-type/' . $icon) }}"
                    alt="icon"
                />
                <p class="system-title m-0" data-key="{{ $key }}_field">{{ $title }}</p>
            </div>

            <div class="d-flex {{ $draggable ? '' : 'd-none' }}" id="header-badge">
                <img
                    id="accordion-chevron"
                    width="16px"
                    src="{{ asset('assets/icons/entity/document-type/chevron-right.svg') }}"
                    alt="chevron"
                />
            </div>

            {{-- <div class="removeButtonBaseField" data-delete-action="{{ $key }}"> --}}
            {{-- <img src="{{ asset('assets/icons/entity/document-type/close-field-base.svg') }}" --}}
            {{-- alt="close-field-base" /> --}}
            {{-- </div> --}}
        </div>
    </div>

    <div class="document-field-accordion-body d-none" id="field-accordion-body-{{ $key }}">
        <div class="document-field-accordion-body-form d-flex flex-column gap-2">
            {{-- Ролі --}}
            @foreach ($roles as $role => $checked)
                <div class="form-check form-switch d-flex align-items-center">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="{{ $role }}_{{ $key }}"
                        data-action="{{ $key }}"
                        data-role="{{ $role }}"
                        @if($checked) checked @endif
                    />
                    <label class="form-check-label ps-75 fw-bold" for="{{ $role }}_{{ $key }}">
                        {{ __('localization.document_types_create_action_item_acordion_' . $role) }}
                    </label>
                </div>
            @endforeach

            <hr />

            {{-- Футер --}}
            <div
                class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
            >
                <button
                    type="button"
                    id="removeButton_{{ $key }}"
                    class="btn btn-flat-danger d-flex align-items-center"
                    data-delete-action="{{ $key }}"
                >
                    <img
                        class="trash-red"
                        src="{{ asset('assets/icons/entity/document-type/trash-red2.svg') }}"
                        alt="trash-red2"
                    />
                    <span>
                        {{ __('localization.document_types_update_fields_text_button_delete') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</li>
