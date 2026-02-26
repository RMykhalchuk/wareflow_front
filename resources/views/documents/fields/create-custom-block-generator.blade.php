<div class="col-12 col-md-6 mb-1">
    @php
        $fieldKey = Str::slug($field['name'], '_');
        $placeholderKey = Str::slug($field['hint'], '_');

        $labelLocalizationKey = 'localization.documents.fields.' . $documentType->kind . '.label.' . $fieldKey;
        $placeholderLocalizationKey = 'localization.documents.fields.' . $documentType->kind . '.placeholder.' . $placeholderKey;
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
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
            </div>

            @break
        @case('date')
            <div class="input-group input-group-merge">
                <input
                    type="text"
                    class="form-control js-current-data flatpickr-basic flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                    id="{{ $key }}"
                    name="{{ $key }}"
                    placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                    aria-describedby="basic-addon6"
                    {{ $field['required'] ? 'required' : '' }}
                />
                <span class="input-group-text" id="basic-addon6">
                    <img src="{{ asset('assets/icons/entity/document/calendar-document.svg') }}" />
                </span>
            </div>

            @break
        @case('dateRange')
            <input
                type="text"
                id="{{ $key }}"
                name="{{ $key }}"
                class="form-control js-current-data flatpickr-range flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                readonly="readonly"
                {{ $field['required'] ? 'required' : '' }}
            />

            @break
        @case('dateTimeRange')
            <div class="row">
                <div class="col-6">
                    <input
                        type="text"
                        id="{{ $key }}_date"
                        name="{{ $key }}[]"
                        class="form-control js-current-data flatpickr-basic flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_data_time_range') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
                <div class="col-3">
                    <input
                        type="text"
                        id="{{ $key }}_time_from"
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
                        name="{{ $key }}[]"
                        class="form-control js-current-data flatpickr-basic text-start flatpickr-input {{ $field['required'] ? 'required-field' : '' }}"
                        placeholder="{{ __('localization.documents_fields_data_time_date') }}"
                        readonly="readonly"
                        {{ $field['required'] ? 'required' : '' }}
                    />
                </div>
                <div class="col-6">
                    <input
                        type="text"
                        id="{{ $key }}_time"
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
                id="{{ $key }}-custom"
                name="{{ $key }}"
                data-placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                data-dictionary="{{ $field['directory'] }}"
                {{ $field['required'] ? 'required' : '' }}
            >
                <option value=""></option>
            </select>

            @break
        @case('label')
            <select
                class="select2 form-select select2-multiple dictionary-search {{ $field['required'] ? 'required-field-select' : '' }}"
                id="{{ $key }}-custom"
                data-dictionary="{{ $field['directory'] }}"
                name="{{ $key }}[]"
                data-placeholder="{{ Lang::has($placeholderLocalizationKey) ? __($placeholderLocalizationKey) : $field['hint'] }}"
                {{ $field['required'] ? 'required' : '' }}
                multiple
            >
                <option value=""></option>
            </select>

            @break
        @case('switch')
            <div class="form-check d-flex align-items-center gap-2 form-check-primary form-switch">
                <input
                    type="checkbox"
                    class="form-check-input {{ $field['required'] ? 'required-field-switch' : '' }}"
                    name="{{ $key }}"
                    id="{{ $key }}"
                    {{ $field['required'] ? 'required' : '' }}
                />
                <div class="invalid-feedback">
                    {{ __('localization.documents.fields.switch_invalid_text') }}
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
            ></textarea>

            @break
    @endswitch
</div>
