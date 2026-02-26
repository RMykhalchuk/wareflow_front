@switch($field['type'])
    @case('text')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/letter-case.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['requiredIsNumber'] ? '' : 'd-none' }}"
                                id="field-badge-required-isNumber"
                            >
                                {{ __('localization.document_types_update_fields_text_field_badge_numeric') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_text_field_badge_required') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_text_field_badge_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_text_input_title') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_text_input_title_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_text_input_title_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_text_input_title_invalid_feedback') }}
                            </div>
                        </div>

                        <div class="mb-1">
                            <label
                                class="form-label"
                                for="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_text_input_description') }}
                            </label>
                            <input
                                id="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_text_input_description_placeholder') }}"
                                value="{{ $field['hint'] }}"
                            />
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="js-form-check-input-isNumber form-check-input"
                                id="requiredCheckIsNumber_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['requiredIsNumber'] ? 'checked' : '' }}
                            />
                            <label
                                class="js-form-check-label-isNumber form-check-label"
                                for="requiredCheckIsNumber_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_text_checkbox_numeric') }}
                            </label>
                        </div>

                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_text_checkbox_required') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_text_button_delete') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('range')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/letter-case.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_range_field_badge_required') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_range_field_badge_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_range_input_title') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_range_input_title_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_range_input_title_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_range_input_title_invalid_feedback') }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_range_checkbox_required') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_range_button_delete') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('date')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/calendar-event.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_date_field_badge_required') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_date_field_badge_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_range_input_title') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_date_input_title_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_date_input_title_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_date_input_title_invalid_feedback') }}
                            </div>
                        </div>

                        <div class="mb-1">
                            <label
                                class="form-label"
                                for="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_date_input_hint') }}
                            </label>
                            <input
                                id="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_date_input_hint_placeholder') }}"
                                value="{{ $field['hint'] }}"
                            />
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_date_checkbox_required') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_date_button_delete') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('dateRange')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/calendar-event.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_date_range_field_badge_required') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_date_range_field_badge_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_range_input_title') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_date_range_input_title_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_date_range_input_title_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_date_range_input_title_invalid_feedback') }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_date_range_checkbox_required') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_date_range_button_delete') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('dateTime')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/calendar-event.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_date_time_field_badge_required') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_date_time_field_badge_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_range_input_title') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_date_time_input_title_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_date_time_input_title_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_date_time_input_title_invalid_feedback') }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_date_time_checkbox_required') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_date_time_button_delete') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('dateTimeRange')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/calendar-event.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_date_time_range_field_badge_required') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_date_time_range_field_badge_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_range_input_title') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_date_time_range_input_title_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_date_time_range_input_title_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_date_time_range_input_title_invalid_feedback') }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_date_time_range_checkbox_required') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_date_time_range_button_delete') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('timeRange')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/clock.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_time_range_field_badge_required') }}
                            </span>

                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_time_range_field_badge_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_range_input_title') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_time_range_input_title_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_time_range_input_title_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_time_range_input_title_invalid_feedback') }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_time_range_checkbox_required') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_time_range_button_delete') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('select')
        <li
            class="group ui-draggable ui-draggable-handle"
            data-id="{{ $field['id'] }}"
            data-system="{{ $field['system'] }}"
        >
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/arrow-down-circle.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_select_required') }}
                            </span>
                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_select_system') }}
                            </span>
                        </div>
                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>
                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="">
                        <div class="js-validate-input mb-1">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_select_field_name_label') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_select_field_name_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_select_field_name_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_select_field_name_invalid_feedback') }}
                            </div>
                        </div>

                        <div class="mb-1">
                            <label
                                class="form-label"
                                for="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_select_hint_label') }}
                            </label>
                            <input
                                id="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_select_hint_placeholder') }}"
                                value="{{ $field['hint'] }}"
                            />
                        </div>

                        <div id="blockDataParam_{{ $field['id'] }}" class="js-blockDataParam">
                            <div
                                id="directoryBlock_{{ $field['id'] }}"
                                class="js-directoryBlock js-error-select2 js-validate-select mb-1 {{ array_key_exists('directory', $field) ? '' : 'd-none' }}"
                            >
                                <label
                                    class="form-label"
                                    for="directorySelect_{{ array_key_exists('directory', $field) }}_{{ $field['type'] }}_{{ $field['id'] }}"
                                >
                                    {{ __('localization.document_types_update_fields_select_directory_label') }}
                                </label>
                                <select
                                    class="select2 form-select required-field-select"
                                    id="directorySelect_{{ array_key_exists('directory', $field) }}_{{ $field['type'] }}_{{ $field['id'] }}"
                                    data-placeholder="{{ __('localization.document_types_update_fields_select_directory_placeholder') }}"
                                    required
                                >
                                    <option value=""></option>

                                    @php
                                        $dictionaryNames = [
                                            'adr' => 'document_types_update_fields_dictionary_adr',
                                            'cell_type' => 'document_types_update_fields_dictionary_cell_type',
                                            'cell_status' => 'document_types_update_fields_dictionary_cell_status',
                                            'company_status' => 'document_types_update_fields_dictionary_company_status',
                                            'country' => 'document_types_update_fields_dictionary_country',
                                            'download_zone' => 'document_types_update_fields_dictionary_download_zone',
                                            'measurement_unit' => 'document_types_update_fields_dictionary_measurement_unit',
                                            'package_type' => 'document_types_update_fields_dictionary_package_type',
                                            'position' => 'document_types_update_fields_dictionary_position',
                                            'settlement' => 'document_types_update_fields_dictionary_settlement',
                                            'street' => 'document_types_update_fields_dictionary_street',
                                            'storage_type' => 'document_types_update_fields_dictionary_storage_type',
                                            'transport_brand' => 'document_types_update_fields_dictionary_transport_brand',
                                            'transport_download' => 'document_types_update_fields_dictionary_transport_download',
                                            'transport_kind' => 'document_types_update_fields_dictionary_transport_kind',
                                            'transport_type' => 'document_types_update_fields_dictionary_transport_type',
                                            'company' => 'document_types_update_fields_dictionary_company',
                                            'warehouse' => 'document_types_update_fields_dictionary_warehouse',
                                            'transport' => 'document_types_update_fields_dictionary_transport',
                                            'additional_equipment' => 'document_types_update_fields_dictionary_additional_equipment',
                                            'user' => 'document_types_update_fields_dictionary_user',
                                            'document_order' => 'document_types_update_fields_dictionary_document_order',
                                            'document_goods_invoice' => 'document_types_update_fields_dictionary_document_goods_invoice',
                                            'currencies' => 'document_types_update_fields_dictionary_currencies',
                                            'cargo_type' => 'document_types_update_fields_dictionary_cargo_type',
                                            'delivery_type' => 'document_types_update_fields_dictionary_delivery_type',
                                            'basis_for_ttn' => 'document_types_update_fields_dictionary_basis_for_ttn',
                                        ];
                                    @endphp

                                    @foreach (\App\Helpers\DictionaryList::list() as $key => $dictionary)
                                        <option
                                            value="{{ $key }}"
                                            {{ array_key_exists('directory', $field) && $field['directory'] == $key ? 'selected' : '' }}
                                        >
                                            {{ isset($dictionaryNames[$key]) ? __('localization.' . $dictionaryNames[$key]) : '-' }}
                                        </option>
                                    @endforeach
                                </select>

                                <button
                                    id="parameterBtnShow_{{ $field['id'] }}"
                                    class="js-parameterBtnShow btn text-primary mt-1"
                                >
                                    {{ __('localization.document_types_update_fields_select_add_custom_options_button') }}
                                </button>
                            </div>

                            <div
                                id="parameterBlock_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                class="js-parameterBlock mb-1 {{ array_key_exists('data', $field) ? '' : 'd-none' }}"
                            >
                                <label class="form-label" for="inputParameter">
                                    {{ __('localization.document_types_update_fields_select_parameter_label') }}
                                </label>
                                <div class="d-flex row mx-0" style="gap: 16px">
                                    <div class="col-9 px-0">
                                        <input
                                            id="inputParameter_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                            class="form-control"
                                            type="text"
                                            placeholder="{{ __('localization.document_types_update_fields_select_parameter_placeholder') }}"
                                        />
                                    </div>

                                    <button
                                        id="addItemParameter_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                        class="btn btn-outline-primary flex-grow-1 col-2 text-primary"
                                    >
                                        {{ __('localization.document_types_update_fields_select_add_button') }}
                                    </button>
                                </div>

                                <ul
                                    id="parameter-list_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                    class="p-0 col-9"
                                >
                                    @if (array_key_exists('data', $field))
                                        @foreach ($field['data'] as $li => $data)
                                            {{-- <script> --}}
                                            {{-- console.log(<?php echo json_encode($data); ?>); // Output the value of $data to the console --}}
                                            {{-- </script> --}}
                                            <li
                                                class="parameter-item"
                                                data-value="{{ $data[0]['name'] }}"
                                                data-checked="{{ $data[0]['is_checked'] }}"
                                            >
                                                <div class="parameter-item-title">
                                                    <img
                                                        src="{{ asset('/assets/icons/entity/document-type/grip-vertical.svg') }}"
                                                    />
                                                    {{ $data[0]['name'] }}
                                                </div>

                                                <div
                                                    class="removeButtonBaseFieldParam align-items-center"
                                                >
                                                    <div
                                                        class="js-input-parameter {{ $data[0]['is_checked'] ? 'checked-js-input-parameter' : '' }}"
                                                    >
                                                        <div
                                                            class="form-check form-check-warning pe-1"
                                                        >
                                                            <input
                                                                type="radio"
                                                                class="form-check-input"
                                                                id="{{ $data[0]['name'] }}"
                                                                {{ $data[0]['is_checked'] ? 'checked' : '' }}
                                                            />
                                                            <label
                                                                class="form-check-label"
                                                                for="{{ $data[0]['name'] }}"
                                                            >
                                                                {{ __('localization.document_types_update_fields_select_default_label') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="removeButtonParemeters_{{ $li }}">
                                                        <img
                                                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                                                            alt="close-field-base"
                                                        />
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>

                                <button
                                    id="addItemInDirectory_{{ $field['id'] }}"
                                    class="js-addItemInDirectory btn mt-1 text-primary"
                                >
                                    {{ __('localization.document_types_update_fields_select_add_directory_button') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_select_required_checkbox') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_select_remove_button') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('label')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/label.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_label_required') }}
                            </span>
                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_label_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>

            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_label_field_name_label') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_label_field_name_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_label_field_name_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_label_field_name_invalid_feedback') }}
                            </div>
                        </div>

                        <div class="mb-1">
                            <label
                                class="form-label"
                                for="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_label_hint_label') }}
                            </label>
                            <input
                                id="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_label_hint_placeholder') }}"
                                value="{{ $field['hint'] }}"
                            />
                        </div>

                        <div class="js-validate-select js-error-select2 mb-1">
                            <label
                                class="form-label"
                                for="descInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_label_directory_label') }}
                            </label>
                            <select
                                class="select2 form-select required-field-select"
                                id="directorySelect_{{ $field['directory'] }}_label_{{ $field['id'] }}"
                                data-placeholder="{{ __('localization.document_types_update_fields_label_directory_placeholder') }}"
                                required
                            >
                                @php
                                    $dictionaryNames = [
                                        'adr' => 'document_types_update_fields_dictionary_adr',
                                        'cell_type' => 'document_types_update_fields_dictionary_cell_type',
                                        'cell_status' => 'document_types_update_fields_dictionary_cell_status',
                                        'company_status' => 'document_types_update_fields_dictionary_company_status',
                                        'country' => 'document_types_update_fields_dictionary_country',
                                        'download_zone' => 'document_types_update_fields_dictionary_download_zone',
                                        'measurement_unit' => 'document_types_update_fields_dictionary_measurement_unit',
                                        'package_type' => 'document_types_update_fields_dictionary_package_type',
                                        'position' => 'document_types_update_fields_dictionary_position',
                                        'settlement' => 'document_types_update_fields_dictionary_settlement',
                                        'street' => 'document_types_update_fields_dictionary_street',
                                        'storage_type' => 'document_types_update_fields_dictionary_storage_type',
                                        'transport_brand' => 'document_types_update_fields_dictionary_transport_brand',
                                        'transport_download' => 'document_types_update_fields_dictionary_transport_download',
                                        'transport_kind' => 'document_types_update_fields_dictionary_transport_kind',
                                        'transport_type' => 'document_types_update_fields_dictionary_transport_type',
                                        'company' => 'document_types_update_fields_dictionary_company',
                                        'warehouse' => 'document_types_update_fields_dictionary_warehouse',
                                        'transport' => 'document_types_update_fields_dictionary_transport',
                                        'additional_equipment' => 'document_types_update_fields_dictionary_additional_equipment',
                                        'user' => 'document_types_update_fields_dictionary_user',
                                        'document_order' => 'document_types_update_fields_dictionary_document_order',
                                        'document_goods_invoice' => 'document_types_update_fields_dictionary_document_goods_invoice',
                                        'currencies' => 'document_types_update_fields_dictionary_currencies',
                                        'cargo_type' => 'document_types_update_fields_dictionary_cargo_type',
                                        'delivery_type' => 'document_types_update_fields_dictionary_delivery_type',
                                        'basis_for_ttn' => 'document_types_update_fields_dictionary_basis_for_ttn',
                                    ];
                                @endphp

                                @foreach (\App\Helpers\DictionaryList::list() as $key => $dictionary)
                                    <option
                                        value="{{ $key }}"
                                        {{ $field['directory'] == $key ? 'selected' : '' }}
                                    >
                                        {{ isset($dictionaryNames[$key]) ? __('localization.' . $dictionaryNames[$key]) : '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr />
                        <div
                            class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                        >
                            <div class="form-check form-check-warning pe-1">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                    {{ $field['required'] ? 'checked' : '' }}
                                />
                                <label
                                    class="form-check-label"
                                    for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                >
                                    {{ __('localization.document_types_update_fields_label_required_checkbox') }}
                                </label>
                            </div>

                            <div>
                                <button
                                    type="button"
                                    id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                    class="btn btn-flat-danger d-flex align-items-center"
                                >
                                    <img
                                        class="trash-red"
                                        src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                    />
                                    <span>
                                        {{ __('localization.document_types_update_fields_label_remove_button') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('switch')
        <li
            class="group ui-draggable ui-draggable-handle"
            data-system="0"
            style="width: 295.256px; height: 61.641px; z-index: 1000"
        >
            <div class="accordion-header ui-accordion-header mb-0 bg-white" data-type="switch">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/checkbox.svg') }}"
                        />
                        <p class="system-title m-0" data-key="switch_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            @if ($field['required'])
                                <span
                                    class="badge badge-light-secondary mx-2 d-none"
                                    id="field-badge-required"
                                >
                                    {{ __('localization.document_types_update_fields_switch_required') }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>
                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>
            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-titleInput text-danger mb-1 d-none">
                            {{ __('localization.document_types_update_fields_switch_field_name_invalid_feedback') }}
                        </div>
                        <label class="form-label" for="titleInput_0_switch">
                            {{ __('localization.document_types_update_fields_switch_field_name_label') }}
                        </label>
                        <input
                            id="titleInput_0_switch"
                            class="form-control"
                            type="text"
                            placeholder="{{ __('localization.document_types_update_fields_switch_field_name_placeholder') }}"
                            value="{{ $field['name'] }}"
                        />
                    </div>
                    <div class="d-none"></div>
                    <div class="d-none"></div>
                </div>
                <hr />
                <div
                    class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                >
                    <div class="form-check form-check-warning pe-1">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="requiredCheck_0_switch"
                            {{ $field['required'] ? 'checked' : '' }}
                        />
                        <label class="form-check-label" for="requiredCheck_0_switch">
                            {{ __('localization.document_types_update_fields_switch_required_checkbox') }}
                        </label>
                    </div>
                    <div class="">
                        <button
                            type="button"
                            id="removeButton_0_switch"
                            class="btn btn-flat-danger d-flex align-items-center"
                        >
                            <img
                                class="trash-red"
                                src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                            />
                            <span>
                                {{ __('localization.document_types_update_fields_switch_remove_button') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('uploadFile')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/upload.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_upload_file_required') }}
                            </span>
                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_upload_file_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>
            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_upload_file_field_name_label') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_upload_file_field_name_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_upload_file_field_name_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_upload_file_field_name_invalid_feedback') }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_upload_file_required_checkbox') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_upload_file_remove_button') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
    @case('comment')
        <li class="group ui-draggable ui-draggable-handle" data-system="{{ $field['system'] }}">
            <div
                class="accordion-header ui-accordion-header mb-0 bg-white"
                data-type="{{ $field['type'] }}"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img
                            class="pe-1"
                            src="{{ asset('/assets/icons/entity/document-type/align-justified.svg') }}"
                        />
                        <p class="system-title m-0" data-key="{{ $field['type'] }}_field">
                            {{ $field['name'] }}
                        </p>
                    </div>
                    <div class="d-flex" id="header-badge">
                        <div>
                            <span
                                class="badge badge-light-secondary mx-2 {{ $field['required'] ? '' : 'd-none' }}"
                                id="field-badge-required"
                            >
                                {{ __('localization.document_types_update_fields_comment_required') }}
                            </span>
                            <span
                                class="badge badge-light-secondary mx-2 {{ isset($field['system']) !== '0' ? '' : 'd-none' }}"
                            >
                                {{ __('localization.document_types_update_fields_comment_system') }}
                            </span>
                        </div>

                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('/assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>

                    <div class="removeButtonBaseField" id="removeButton">
                        <img
                            src="{{ asset('/assets/icons/entity/document-type/close-field-base.svg') }}"
                            alt="close-field-base"
                        />
                    </div>
                </div>
            </div>
            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                        <div class="js-validate-input">
                            <label
                                class="form-label"
                                for="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                            >
                                {{ __('localization.document_types_update_fields_comment_field_name_label') }}
                            </label>
                            <input
                                id="titleInput_{{ $field['system'] }}_{{ $field['type'] }}"
                                class="form-control required-field"
                                type="text"
                                placeholder="{{ __('localization.document_types_update_fields_comment_field_name_placeholder') }}"
                                value="{{ $field['name'] }}"
                                required
                            />
                            <div class="valid-feedback">
                                {{ __('localization.document_types_update_fields_comment_field_name_valid_feedback') }}
                            </div>
                            <div class="invalid-feedback">
                                {{ __('localization.document_types_update_fields_comment_field_name_invalid_feedback') }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                    >
                        <div class="form-check form-check-warning pe-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                                {{ $field['required'] ? 'checked' : '' }}
                            />
                            <label
                                class="form-check-label"
                                for="requiredCheck_{{ $field['system'] }}_{{ $field['type'] }}_{{ $field['id'] }}_{{ $typeBlock }}"
                            >
                                {{ __('localization.document_types_update_fields_comment_required_checkbox') }}
                            </label>
                        </div>

                        <div>
                            <button
                                type="button"
                                id="removeButton{{ $field['system'] }}_{{ $field['type'] }}"
                                class="btn btn-flat-danger d-flex align-items-center"
                            >
                                <img
                                    class="trash-red"
                                    src="{{ asset('/assets/icons/entity/document-type/trash-red2.svg') }}"
                                />
                                <span>
                                    {{ __('localization.document_types_update_fields_comment_remove_button') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @break
@endswitch
