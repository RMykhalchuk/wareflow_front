<x-modal.base id="customField" size="modal-lg" style="max-width: 800px !important">
    <x-slot name="header">
        <div class="d-flex flex-column m-auto">
            <x-ui.section-card-title level="3" class="modal-title text-center">
                {{ __('localization.document_types_create_add_new_fields_modal_header_title') }}
            </x-ui.section-card-title>

            <p class="text-center" id="custom-modal-title">
                {{ __('localization.document_types_create_add_new_fields_modal_header_subtitle') }}
            </p>
        </div>

        <button
            type="button"
            class="btn-close position-absolute top-0 end-0"
            id="custom-close-btn"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
    </x-slot>

    <x-slot name="body">
        <div class="field-type-list">
            <div class="input-group input-group-merge mb-2">
                <span class="input-group-text" id="basic-addon-search2">
                    <i data-feather="search"></i>
                </span>
                <input
                    type="text"
                    class="form-control ps-1"
                    id="searchBarItemModal"
                    placeholder="{{ __('localization.document_types_create_add_new_fields_modal_body_field_list_search_placeholder') }}"
                    aria-label="Search..."
                    aria-describedby="basic-addon-search2"
                />
            </div>

            <x-section-title>
                {{ __('localization.document_types_create_add_new_fields_modal_body_field_list_title') }}
            </x-section-title>

            <ul
                id="modalFieldTypeList"
                style="max-height: 420px; overflow-y: scroll"
                class="ps-0 mt-1"
            >
                <x-card.field-type-item
                    id="field-type-text"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_text"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_text_description"
                    icon-name="type-text.svg"
                    alt-text="type-text"
                />

                <x-card.field-type-item
                    id="field-type-range"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_range"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_range_description"
                    icon-name="type-text-range.svg"
                    alt-text="text-range"
                />

                <x-card.field-type-item
                    id="field-type-select"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_select"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_select_description"
                    icon-name="type-select.svg"
                    alt-text="type-select"
                />

                <x-card.field-type-item
                    id="field-type-label"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_multiselect"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_multiselect_description"
                    icon-name="type-multiselect.svg"
                    alt-text="type-label"
                />

                <x-card.field-type-item
                    id="field-type-date"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date_description"
                    icon-name="type-date.svg"
                    alt-text="type-date"
                />

                <x-card.field-type-item
                    id="field-type-dateRange"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date_range"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date_range_description"
                    icon-name="type-date-range.svg"
                    alt-text="type-date-range"
                />

                <x-card.field-type-item
                    id="field-type-dateTime"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date_time"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date_time_description"
                    icon-name="type-date-time.svg"
                    alt-text="type-date-time"
                />

                <x-card.field-type-item
                    id="field-type-dateTimeRange"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date_time_range"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_date_time_range_description"
                    icon-name="type-date-time-range.svg"
                    alt-text="type-date-time-range"
                />

                <x-card.field-type-item
                    id="field-type-timeRange"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_time_range"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_time_range_description"
                    icon-name="type-time-range.svg"
                    alt-text="type-time-range"
                />

                <x-card.field-type-item
                    id="field-type-switch"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_switch"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_switch_description"
                    icon-name="type-switch.svg"
                    alt-text="type-switch"
                />

                <x-card.field-type-item
                    id="field-type-uploadFile"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_upload_file"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_upload_file_description"
                    icon-name="type-upload-file.svg"
                    alt-text="type-upload-file"
                />

                <x-card.field-type-item
                    id="field-type-comment"
                    title-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_comment"
                    description-key="localization.document_types_create_add_new_fields_modal_body_field_list_type_comment_description"
                    icon-name="type-comment.svg"
                    alt-text="type-comment"
                />
            </ul>
        </div>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-text">
            <x-form.input-text
                id="additional-settings-field-type-text-title"
                name="additional-settings-field-type-text-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_title_placeholder"
                class="col-12 mb-1"
            />

            <x-form.textarea
                id="additional-settings-field-type-text-desc"
                name="additional-settings-field-type-text-desc"
                rows="3"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_desc"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_desc_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-range">
            <x-form.input-text
                id="additional-settings-field-type-range-title"
                name="additional-settings-field-type-range-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_range_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_range_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-select">
            <x-form.input-text
                id="additional-settings-field-type-select-title"
                name="additional-settings-field-type-select-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_title_placeholder"
                class="col-12 mb-1"
            />

            <x-form.textarea
                id="additional-settings-field-type-select-desc"
                name="additional-settings-field-type-select-desc"
                rows="3"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_desc"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_desc_placeholder"
                class="col-12 mb-1"
            />

            <div class="blockDataParam">
                <div id="directoryBlock" class="mb-1">
                    <label class="form-label" for="directoryBlock">
                        {{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_directory') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="additional-settings-field-type-select-model"
                        data-placeholder="{{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_directory_placeholder') }}"
                    >
                        <option value=""></option>
                        @foreach (\App\Helpers\DictionaryList::list() as $key => $dictionaryName)
                            <option value="{{ $key }}">
                                {{ $dictionaryName }}
                            </option>
                        @endforeach
                    </select>

                    <button id="parameterBtnShow" class="btn text-primary mt-1">
                        {{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add_custom_options') }}
                    </button>
                </div>

                <div id="parameterBlock" class="mb-1 d-none">
                    <label class="form-label" for="inputParameter">
                        {{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_parameter') }}
                    </label>
                    <div class="d-flex row mx-0" style="gap: 16px">
                        <div class="col-9 px-0">
                            <input
                                id="inputParameter"
                                class="form-control"
                                type="text"
                                placeholder="{{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_parameter_placeholder') }}"
                            />
                        </div>

                        <button
                            id="customAddItemParameter"
                            class="btn btn-outline-primary flex-grow-1 col-2 text-primary"
                        >
                            {{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add') }}
                        </button>
                    </div>
                    <ul class="p-0 parameter-list col-9"></ul>

                    <button id="addItemInDirectory" class="btn text-primary">
                        {{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add_directory') }}
                    </button>
                </div>
            </div>
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-label">
            <x-form.input-text
                id="additional-settings-field-type-label-title"
                name="additional-settings-field-type-label-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_title_placeholder"
                class="col-12 mb-1"
            />

            <x-form.textarea
                id="additional-settings-field-type-label-desc"
                name="additional-settings-field-type-label-desc"
                rows="3"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_desc"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_desc_placeholder"
                class="col-12 mb-1"
            />

            <div class="mb-1">
                <label class="form-label" for="select2-hide-search">
                    {{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_directory') }}
                </label>
                <select
                    class="select2 form-select"
                    id="additional-settings-field-type-label-parameter"
                    data-placeholder="{{ __('localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_directory_placeholder') }}"
                >
                    <option value=""></option>
                    @foreach (\App\Helpers\DictionaryList::list() as $key => $dictionaryName)
                        <option value="{{ $key }}">
                            {{ $dictionaryName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-date">
            <x-form.input-text
                id="additional-settings-field-type-date-title"
                name="additional-settings-field-type-date-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_title_placeholder"
                class="col-12 mb-1"
            />

            <x-form.textarea
                id="additional-settings-field-type-date-desc"
                name="additional-settings-field-type-date-desc"
                rows="3"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_desc"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_desc_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-dateRange">
            <x-form.input-text
                id="additional-settings-field-type-dateRange-title"
                name="additional-settings-field-type-dateRange-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_range_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_range_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-dateTime">
            <x-form.input-text
                id="additional-settings-field-type-dateTime-title"
                name="additional-settings-field-type-dateTime-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-dateTimeRange">
            <x-form.input-text
                id="additional-settings-field-type-dateTimeRange-title"
                name="additional-settings-field-type-dateTimeRange-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_range_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_range_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-timeRange">
            <x-form.input-text
                id="additional-settings-field-type-timeRange-title"
                name="additional-settings-field-type-timeRange-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_time_range_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_time_range_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-switch">
            <x-form.input-text
                id="additional-settings-field-type-switch-title"
                name="additional-settings-field-type-switch-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_switch_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_switch_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-uploadFile">
            <x-form.input-text
                id="additional-settings-field-type-uploadFile-title"
                name="additional-settings-field-type-uploadFile-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_upload_file_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_upload_file_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>

        <x-card.additional-settings-wrapper id="additional-settings-field-type-comment">
            <x-form.input-text
                id="additional-settings-field-type-comment-title"
                name="additional-settings-field-type-comment-title"
                label="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_comment_title"
                placeholder="localization.document_types_create_add_new_fields_modal_body_additional_settings_field_type_comment_title_placeholder"
                class="col-12 mb-1"
            />
        </x-card.additional-settings-wrapper>
    </x-slot>

    <x-slot name="footer">
        <div class="d-flex flex-grow-1 justify-content-between">
            <button type="button" class="btn btn-flat-secondary" id="back-custom-btn">
                <img
                    class="nav-img"
                    src="{{ asset('assets/icons/entity/document-type/arrow-left.svg') }}"
                    alt="arrow-left"
                />
                {{ __('localization.document_types_create_add_new_fields_modal_footer_back') }}
            </button>
            <button type="button" class="btn btn-primary" id="create-custom-btn">
                {{ __('localization.document_types_create_add_new_fields_modal_footer_create') }}
            </button>
        </div>
    </x-slot>
</x-modal.base>
