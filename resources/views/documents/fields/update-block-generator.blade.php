<div class="col-12 col-md-6 mb-1">
    @php
        $fieldKey = Str::slug($field['name'], '_');
        $placeholderKey = Str::slug($field['hint'], '_');

        $labelLocalizationKey = 'localization.documents.fields.' . $documentType->kind . '.label.' . $fieldKey;
        $placeholderLocalizationKey = 'localization.documents.fields.' . $documentType->kind . '.placeholder.' . $placeholderKey;

        $fieldValue = $document->data()['header'][$key] ?? '';
        $fieldValueIds = $document->data()['header_ids'][$key . '_id'] ?? null;
    @endphp

    <label class="form-label" for="{{ $key }}">
        <span>
            {{ Lang::has($labelLocalizationKey) ? __($labelLocalizationKey) : $field['name'] }}
        </span>
        <span class="text-danger fs-5">{{ $field['required'] ? '*' : '' }}</span>
    </label>

    @switch($field['type'])
        @case('text')
            <input
                type="{{ isset($field['requiredIsNumber']) ? 'number' : 'text' }}"
                value="{{ $fieldValue }}"
                class="form-control {{ $field['required'] ? 'required-field' : '' }}"
                placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                name="{{ $key }}"
                id="{{ $key }}"
                {{ $field['required'] ? 'required' : '' }}
            />

            @break
        @case('range')
            <div class="row">
                <div class="col-6">
                    <input
                        type="number"
                        class="form-control col-6 {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_range_from') }}"
                        name="{{ $key }}[]"
                        id="{{ $key }}"
                        value="{{ $fieldValue[0] ?? '' }}"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
                <div class="col-6">
                    <input
                        type="number"
                        class="form-control col-6 {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_range_to') }}"
                        name="{{ $key }}[]"
                        id="{{ $key }}"
                        value="{{ $fieldValue[1] ?? '' }}"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
            </div>

            @break
        @case('date')
            <div class="input-group input-group-merge">
                <input
                    type="text"
                    class="form-control flatpickr-basic flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                    id="{{ $key }}"
                    name="{{ $key }}"
                    placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                    value="{{ $fieldValue }}"
                    aria-describedby="{{ $key }}-addon6"
                    {{ $field['required'] ? 'required' : '' }}
                />
                <span class="input-group-text" id="{{ $key }}-addon6">
                    <img src="{{ asset('assets/icons/entity/document/calendar-document.svg') }}" />
                </span>
            </div>

            @break
        @case('dateRange')
            <input
                type="text"
                id="{{ $key }}"
                name="{{ $key }}"
                class="form-control flatpickr-range flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                readonly="readonly"
                value="{{ $fieldValue[0] ?? '' }} to {{ $fieldValue[1] ?? '' }}"
                {{ $field['required'] ? 'required' : '' }}
            />

            @break
        @case('dateTimeRange')
            <div class="row">
                <div class="col-6">
                    <input
                        type="text"
                        id="{{ $key }}_date"
                        value="{{ $fieldValue[0] ?? '' }}"
                        name="{{ $key }}[]"
                        class="form-control flatpickr-basic flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_data_time_range') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
                <div class="col-3">
                    <input
                        type="text"
                        id="{{ $key }}_time_from"
                        value="{{ $fieldValue[1] ?? '' }}"
                        name="{{ $key }}[]"
                        class="form-control flatpickr-time text-start flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_data_time_range_from') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
                <div class="col-3">
                    <input
                        type="text"
                        id="{{ $key }}_time_to"
                        value="{{ $fieldValue[2] ?? '' }}"
                        name="{{ $key }}[]"
                        class="form-control flatpickr-time text-start flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_data_time_range_to') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
            </div>

            @break
        @case('dateTime')
            <div class="row">
                <div class="col-6">
                    <input
                        type="text"
                        id="{{ $key }}_date"
                        value="{{ $fieldValue[0] ?? '' }}"
                        name="{{ $key }}[]"
                        class="form-control flatpickr-basic text-start flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_data_time_date') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
                <div class="col-6">
                    <input
                        type="text"
                        id="{{ $key }}_time"
                        value="{{ $fieldValue[1] ?? '' }}"
                        name="{{ $key }}[]"
                        class="form-control flatpickr-time text-start flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_data_time_time') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
            </div>

            @break
        @case('timeRange')
            <div class="row">
                <div class="col-6">
                    <input
                        type="text"
                        id="{{ $key }}_from"
                        value="{{ $fieldValue[0] ?? '' }}"
                        name="{{ $key }}[]"
                        class="form-control flatpickr-time text-start flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_time_range_from') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
                <div class="col-6">
                    <input
                        type="text"
                        id="{{ $key }}_to"
                        value="{{ $fieldValue[1] ?? '' }}"
                        name="{{ $key }}[]"
                        class="form-control flatpickr-time text-start flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_time_range_to') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
            </div>

            @break
        @case('select')
            <select
                class="select2 form-select {{ $field['required'] ? 'required-field-select' : '' }} dictionary-search"
                id="{{ $key }}"
                name="{{ $key }}"
                data-id="{{ $fieldValueIds ? '[' . $fieldValueIds . ']' : '' }}"
                data-placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                data-dictionary="{{ $field['directory'] }}"
                {{ $field['required'] ? 'required' : '' }}
            ></select>

            @break
        @case('label')
            <select
                class="select2 form-select select2-multiple dictionary-search {{ $field['required'] ? 'required-field-select' : '' }}"
                id="{{ $key }}"
                data-id="{{ $fieldValueIds ? '[' . implode(',', (array) $fieldValueIds) . ']' : '' }}"
                data-dictionary="{{ $field['directory'] }}"
                name="{{ $key }}[]"
                data-placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                {{ $field['required'] ? 'required' : '' }}
                multiple
            ></select>

            @break
        @case('switch')
            <div class="form-check d-flex align-items-center gap-2 form-check-primary form-switch">
                <input
                    type="checkbox"
                    {{ $fieldValue == 'on' ? 'checked' : '' }}
                    class="form-check-input {{ $field['required'] ? 'required-field-switch' : '' }}"
                    name="{{ $key }}"
                    id="{{ $key }}"
                    {{ $field['required'] ? 'required' : '' }}
                />
                <div class="invalid-feedback">
                    {{ __('localization.documents_fields_switch_invalid_text') }}
                </div>
            </div>

            @break
        @case('uploadFile')
            <input
                class="form-control upload-file {{ $field['required'] ? 'required-field' : '' }}"
                type="file"
                multiple
                {{ $field['required'] ? 'required' : '' }}
                name="{{ $key }}"
                id="{{ $key }}"
            />

            @break
        @case('comment')
            <textarea
                class="form-control {{ $field['required'] ? 'required-field' : '' }}"
                id="{{ $key }}"
                rows="3"
                name="{{ $key }}"
                placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                {{ $field['required'] ? 'required' : '' }}
            >
{{ $fieldValue }}</textarea
            >

            @break
    @endswitch
</div>
