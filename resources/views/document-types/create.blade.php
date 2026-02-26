@extends('layouts.admin')

@section('title', __('localization.document_types_create_title'))

@section('page-style')
    
@endsection

@section('before-style')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/vendors.min.css') }}" />

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('vendors/css/extensions/dragula.min.css') }}"
    />
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Page CSS-->
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}"
    />
    <!-- END: Page CSS-->
@endsection

@section('table-js')
    @include('layouts.table-scripts')
@endsection

@section('content')
    @php
        $documentKind = request()->get('document_kind', 'arrival');
    @endphp

    <x-layout.container fluid>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/document-type/',
                            'name' => __('localization.document_types_create_breadcrumb_text_1'),
                        ],
                        ['name' => __('localization.document_types_create_breadcrumb_text_2')],
                    ],
                ]
            )
            <x-ui.header-actions>
                <button type="button" id="draft-save" class="btn btn-flat-dark">
                    {{ __('localization.document_types_create_save_draft') }}
                </button>
                <button type="submit" id="doctype-save" class="btn btn-primary">
                    {{ __('localization.document_types_create_save') }}
                </button>
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested>
                <x-slot:header>
                    @php
                        $titles = [
                            'arrival' => 'incoming', // Прихідний
                            'outcome' => 'outgoing', // Розхідний
                            'inner' => 'internal', // Внутрішній
                            'neutral' => 'neutral', // Нейтральний
                        ];
                        $typeKey = $titles[$documentKind] ?? 'incoming';
                        $titleKey = "localization.document_types.create.{$typeKey}";
                    @endphp

                    <x-section-title>
                        {{ __($titleKey) }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <div class="d-flex row align-items-end mx-0">
                        <div class="col-12 col-md-6 ps-0">
                            <div class="create-type-input">
                                <div
                                    class="col-12 col-md-12 col-lg-12"
                                    id="document-type-name-form"
                                >
                                    <div class="mb-1">
                                        <input
                                            type="text"
                                            id="document-type-name"
                                            class="form-control required-field"
                                            placeholder="{{ __('localization.document_types_create_name_placeholder') }}"
                                            aria-label="{{ __('localization.document_types_create_name_aria_label') }}"
                                            aria-describedby="document-type-name"
                                            required
                                        />
                                        <div class="valid-feedback">
                                            {{ __('localization.document_types_create_valid_feedback') }}
                                        </div>
                                        <div class="invalid-feedback">
                                            {{ __('localization.document_types_create_invalid_feedback') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-tabs.group navClass="css-custom-nav-tabs">
                        @slot('items')
                            <x-tabs.item
                                id="header"
                                title="{{ __('localization.document_types.tabs.header') }}"
                                :active="true"
                            />
                            <x-tabs.item
                                id="documents"
                                title="{{ __('localization.document_types.tabs.documents') }}"
                                :disabled="true"
                            />
                            <x-tabs.item
                                id="action"
                                title="{{ __('localization.document_types.tabs.actions') }}"
                            />
                            <x-tabs.item
                                id="task"
                                title="{{ __('localization.document_types.tabs.tasks') }}"
                            />
                        @endslot

                        @slot('content')
                            <x-tabs.content id="header" :active="true">
                                <div class="document-fields px-0">
                                    <div class="row mx-0">
                                        <div class="col-12 col-lg-8 px-0">
                                            <div
                                                class="fields-list border-bottom overflow-y-auto"
                                                style="height: 550px"
                                            >
                                                {{-- Header --}}
                                                <div>
                                                    <div
                                                        id="document-type-empty-header-error"
                                                        class="form-label text-danger d-none mb-1 ps-1"
                                                    >
                                                        {{ __('localization.document_types_create_document_fields_empty_header_error') }}
                                                    </div>
                                                    <div
                                                        id="accordion-field-header"
                                                        class="accordion-field-header align-items-center d-flex justify-content-between"
                                                    >
                                                        <div class="d-flex align-items-center">
                                                            <h4
                                                                id="accordion-field-title"
                                                                class="m-0 fw-bolder"
                                                            >
                                                                {{ __('localization.document_types_create_document_fields_main_info') }}
                                                            </h4>
                                                            <input
                                                                id="header-block-title-input"
                                                                type="text"
                                                                class="m-0 fw-bolder p-0 w-100 header-block-title-input bg-transparent border-0 d-none"
                                                                value="{{ __('localization.document_types_create_document_fields_main_info') }}"
                                                            />
                                                        </div>
                                                    </div>

                                                    @if ($documentKind === 'arrival' || $documentKind === 'outcome')
                                                        <ul class="sortableListStyle">
                                                            <li
                                                                class="group ui-draggable ui-draggable-handle"
                                                                data-desc="Виберіть значення"
                                                                data-system="true"
                                                            >
                                                                <div
                                                                    class="accordion-header ui-accordion-header mb-0 bg-white"
                                                                    data-type="select"
                                                                >
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center"
                                                                    >
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-start"
                                                                        >
                                                                            <img
                                                                                class="pe-1"
                                                                                src="/assets/icons/entity/document-type/arrow-down-circle.svg"
                                                                                alt="select"
                                                                            />
                                                                            <p
                                                                                class="system-title m-0"
                                                                                data-key="select_field"
                                                                            >
                                                                                Постачальник
                                                                            </p>
                                                                        </div>

                                                                        <div
                                                                            class="d-flex"
                                                                            id="header-badge"
                                                                        >
                                                                            <div>
                                                                                <span
                                                                                    class="badge badge-light-secondary mx-2"
                                                                                    id="field-badge-required"
                                                                                >
                                                                                    Обов'язкове
                                                                                </span>
                                                                                <span
                                                                                    class="badge badge-light-secondary mx-2"
                                                                                >
                                                                                    Системне
                                                                                </span>
                                                                            </div>
                                                                            <div
                                                                                class="js-chevron-configurator"
                                                                            >
                                                                                <img
                                                                                    id="accordion-chevron"
                                                                                    width="16px"
                                                                                    src="/assets/icons/entity/document-type/chevron-right.svg"
                                                                                    alt="chevron"
                                                                                    class="accordion-chevron-active"
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="document-field-accordion-body d-none"
                                                                    id="field-accordion-body"
                                                                >
                                                                    <div
                                                                        class="document-field-accordion-body-form"
                                                                    >
                                                                        <div class="mb-1">
                                                                            <div
                                                                                class="js-validate-input"
                                                                            >
                                                                                <label
                                                                                    class="form-label"
                                                                                    for="titleInput_true_select"
                                                                                >
                                                                                    Назва поля
                                                                                </label>
                                                                                <input
                                                                                    id="titleInput_true_select"
                                                                                    class="form-control required-field"
                                                                                    type="text"
                                                                                    placeholder="Назва поля приклад"
                                                                                    disabled
                                                                                    value="Постачальник"
                                                                                    required=""
                                                                                />
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-1">
                                                                            <label
                                                                                class="form-label"
                                                                                for="descInput_true_select"
                                                                            >
                                                                                Підказка
                                                                            </label>
                                                                            <input
                                                                                id="descInput_true_select"
                                                                                class="form-control"
                                                                                disabled
                                                                                type="text"
                                                                                placeholder="Поясніть як користувачі можуть використовувати це поле"
                                                                                value="Виберіть значення"
                                                                            />
                                                                        </div>

                                                                        <div
                                                                            id="directoryBlock_true_default_select_1_header"
                                                                            class="js-error-select2 js-directoryBlock js-validate-select mb-1"
                                                                        >
                                                                            <label
                                                                                class="form-label js-label-directorySelect"
                                                                                for="directorySelect_true_select"
                                                                            >
                                                                                Довідник
                                                                            </label>
                                                                            <select
                                                                                id="directorySelect_true_select_52_copy_1_header"
                                                                                disabled
                                                                                class="select2 form-select required-field-select select2-hidden-accessible"
                                                                                required=""
                                                                                data-placeholder="Виберіть довідник для цього селекту"
                                                                                tabindex="-1"
                                                                                aria-hidden="true"
                                                                            >
                                                                                <option
                                                                                    value=""
                                                                                ></option>
                                                                                <option
                                                                                    value="company"
                                                                                    selected
                                                                                >
                                                                                    Компанії
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <hr />
                                                                    <div
                                                                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                                                                    >
                                                                        <div
                                                                            class="form-check form-check-warning pe-1"
                                                                        >
                                                                            <input
                                                                                type="checkbox"
                                                                                checked
                                                                                disabled
                                                                                class="js-form-check-input form-check-input"
                                                                                id="requiredCheck_true_select_default_1_header"
                                                                            />
                                                                            <label
                                                                                class="js-form-check-label form-check-label"
                                                                                for="requiredCheck_true_select_default_1_header"
                                                                            >
                                                                                Обов'язкове
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li
                                                                class="group ui-draggable ui-draggable-handle"
                                                                data-desc="Виберіть значення"
                                                                data-system="true"
                                                            >
                                                                <div
                                                                    class="accordion-header ui-accordion-header mb-0 bg-white"
                                                                    data-type="select"
                                                                >
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center"
                                                                    >
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-start"
                                                                        >
                                                                            <img
                                                                                class="pe-1"
                                                                                src="/assets/icons/entity/document-type/arrow-down-circle.svg"
                                                                                alt="select"
                                                                            />
                                                                            <p
                                                                                class="system-title m-0"
                                                                                data-key="select_field"
                                                                            >
                                                                                Місце приймання
                                                                            </p>
                                                                        </div>

                                                                        <div
                                                                            class="d-flex"
                                                                            id="header-badge"
                                                                        >
                                                                            <div>
                                                                                <span
                                                                                    class="badge badge-light-secondary mx-2"
                                                                                    id="field-badge-required"
                                                                                >
                                                                                    Обов'язкове
                                                                                </span>
                                                                                <span
                                                                                    class="badge badge-light-secondary mx-2"
                                                                                >
                                                                                    Системне
                                                                                </span>
                                                                            </div>
                                                                            <div
                                                                                class="js-chevron-configurator"
                                                                            >
                                                                                <img
                                                                                    id="accordion-chevron"
                                                                                    width="16px"
                                                                                    src="/assets/icons/entity/document-type/chevron-right.svg"
                                                                                    alt="chevron"
                                                                                    class="accordion-chevron-active"
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="document-field-accordion-body d-none"
                                                                    id="field-accordion-body"
                                                                >
                                                                    <div
                                                                        class="document-field-accordion-body-form"
                                                                    >
                                                                        <div class="mb-1">
                                                                            <div
                                                                                class="js-validate-input"
                                                                            >
                                                                                <label
                                                                                    class="form-label"
                                                                                    for="titleInput_true_select"
                                                                                >
                                                                                    Назва поля
                                                                                </label>
                                                                                <input
                                                                                    id="titleInput_true_select"
                                                                                    class="form-control required-field"
                                                                                    type="text"
                                                                                    placeholder="Назва поля приклад"
                                                                                    disabled
                                                                                    value="Місце приймання"
                                                                                    required=""
                                                                                />
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-1">
                                                                            <label
                                                                                class="form-label"
                                                                                for="descInput_true_select"
                                                                            >
                                                                                Підказка
                                                                            </label>
                                                                            <input
                                                                                id="descInput_true_select"
                                                                                class="form-control"
                                                                                disabled
                                                                                type="text"
                                                                                placeholder="Поясніть як користувачі можуть використовувати це поле"
                                                                                value="Виберіть значення"
                                                                            />
                                                                        </div>

                                                                        <div
                                                                            id="directoryBlock_true_default_select_2_header"
                                                                            class="js-error-select2 js-directoryBlock js-validate-select mb-1"
                                                                        >
                                                                            <label
                                                                                class="form-label js-label-directorySelect"
                                                                                for="directorySelect_true_select"
                                                                            >
                                                                                Довідник
                                                                            </label>
                                                                            <select
                                                                                id="directorySelect_true_select_52_copy_1_header"
                                                                                disabled
                                                                                class="select2 form-select required-field-select select2-hidden-accessible"
                                                                                required=""
                                                                                data-placeholder="Виберіть довідник для цього селекту"
                                                                                tabindex="-1"
                                                                                aria-hidden="true"
                                                                            >
                                                                                <option
                                                                                    value=""
                                                                                ></option>
                                                                                {{-- <option value="adr" data-select2-id="45">АДР</option><option value="cell_type" data-select2-id="46">Тип комірки</option><option value="cell_status" data-select2-id="47">Статус комірки</option><option value="company_status" data-select2-id="48">Статус компанії</option><option value="country" data-select2-id="49">Країна</option><option value="download_zone" data-select2-id="50">Зона завантаження</option><option value="measurement_unit" data-select2-id="51">Одиниці вимірювання</option><option value="package_type" data-select2-id="52">Тип пакування</option><option value="position" data-select2-id="53">Роль користувача</option><option value="settlement" data-select2-id="54">Місто</option><option value="street" data-select2-id="55">Вулиця</option><option value="storage_type" data-select2-id="56">Тип сховища</option><option value="transport_brand" data-select2-id="57">Бренд транспорту</option><option value="transport_download" data-select2-id="58">Тип завантаження</option><option value="transport_kind" data-select2-id="59">Вид транспорту</option><option value="transport_type" data-select2-id="60">Тип транспорту</option><option value="company" data-select2-id="61">Компанії</option><option value="warehouse" data-select2-id="62">Склад</option><option value="transport" data-select2-id="63">Транспорт</option><option value="additional_equipment" data-select2-id="64">Додаткове обладнання</option><option value="user" data-select2-id="65">Користувачі</option><option value="document_order" data-select2-id="66">Замовлення (документи)</option><option value="document_goods_invoice" data-select2-id="67">Товарна накладна (документи)</option><option value="currencies" data-select2-id="68">Валюта</option><option value="cargo_type" data-select2-id="69">Тип вантажу</option><option value="delivery_type" data-select2-id="70">Тип доставки</option><option value="basis_for_ttn" data-select2-id="71">Підстава для ТТН</option> --}}
                                                                                <option
                                                                                    value="cell_type"
                                                                                    selected
                                                                                    data-select2-id="46"
                                                                                >
                                                                                    Комірки
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <hr />
                                                                    <div
                                                                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                                                                    >
                                                                        <div
                                                                            class="form-check form-check-warning pe-1"
                                                                        >
                                                                            <input
                                                                                type="checkbox"
                                                                                checked
                                                                                disabled
                                                                                class="js-form-check-input form-check-input"
                                                                                id="requiredCheck_true_select_default_2_header"
                                                                            />
                                                                            <label
                                                                                class="js-form-check-label form-check-label"
                                                                                for="requiredCheck_true_select_default_2_header"
                                                                            >
                                                                                Обов'язкове
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li
                                                                class="group ui-draggable ui-draggable-handle"
                                                                data-desc="Введіть текст"
                                                                data-system="true"
                                                            >
                                                                <div
                                                                    class="accordion-header ui-accordion-header mb-0 bg-white"
                                                                    data-type="text"
                                                                >
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center"
                                                                    >
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-start"
                                                                        >
                                                                            <img
                                                                                class="pe-1"
                                                                                src="/assets/icons/entity/document-type/letter-case.svg"
                                                                                alt="text"
                                                                            />
                                                                            <p
                                                                                class="system-title m-0"
                                                                                data-key="text_field"
                                                                            >
                                                                                Коментар
                                                                            </p>
                                                                        </div>

                                                                        <div
                                                                            class="d-flex"
                                                                            id="header-badge"
                                                                        >
                                                                            <div>
                                                                                <span
                                                                                    class="badge badge-light-secondary mx-2"
                                                                                    id="field-badge-required"
                                                                                >
                                                                                    Обов'язкове
                                                                                </span>
                                                                                <span
                                                                                    class="badge badge-light-secondary mx-2"
                                                                                >
                                                                                    Системне
                                                                                </span>
                                                                            </div>
                                                                            <div
                                                                                class="js-chevron-configurator"
                                                                            >
                                                                                <img
                                                                                    id="accordion-chevron"
                                                                                    width="16px"
                                                                                    src="/assets/icons/entity/document-type/chevron-right.svg"
                                                                                    alt="chevron"
                                                                                    class=""
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="document-field-accordion-body d-none"
                                                                    id="field-accordion-body"
                                                                >
                                                                    <div
                                                                        class="document-field-accordion-body-form"
                                                                    >
                                                                        <div class="mb-1">
                                                                            <div
                                                                                class="js-validate-input"
                                                                            >
                                                                                <label
                                                                                    class="form-label"
                                                                                    for="titleInput_true_text"
                                                                                >
                                                                                    Назва поля
                                                                                </label>
                                                                                <input
                                                                                    id="titleInput_true_text"
                                                                                    disabled
                                                                                    class="form-control required-field"
                                                                                    type="text"
                                                                                    placeholder="Назва поля приклад"
                                                                                    value="Коментар"
                                                                                    required=""
                                                                                />
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-1">
                                                                            <label
                                                                                class="form-label"
                                                                                for="descInput_true_text"
                                                                            >
                                                                                Підказка
                                                                            </label>
                                                                            <input
                                                                                id="descInput_true_text"
                                                                                disabled
                                                                                class="form-control"
                                                                                type="text"
                                                                                placeholder="Поясніть як користувачі можуть використовувати це поле"
                                                                                value="Введіть коментар"
                                                                            />
                                                                        </div>
                                                                    </div>
                                                                    <hr />
                                                                    <div
                                                                        class="document-field-accordion-body-footer d-flex align-items-center justify-content-end"
                                                                    >
                                                                        <div
                                                                            class="form-check form-check-warning pe-1"
                                                                        >
                                                                            <input
                                                                                type="checkbox"
                                                                                checked
                                                                                disabled
                                                                                class="js-form-check-input form-check-input"
                                                                                id="requiredCheck_true_text_0_copy_1_header"
                                                                            />
                                                                            <label
                                                                                class="js-form-check-label form-check-label"
                                                                                for="requiredCheck_true_text_0_copy_1_header"
                                                                            >
                                                                                Обов'язкове
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    @endif

                                                    <ul
                                                        class="sortableList sortableListStyle"
                                                        data-type-block="header"
                                                        id="header_fields"
                                                    ></ul>
                                                </div>
                                            </div>

                                            {{-- Add new block --}}
                                            <div
                                                class="d-flex justify-content-center align-items-center gap-1 my-50 px-1"
                                            >
                                                <button
                                                    class="btn btn-flat-dark col-auto"
                                                    id="add-new-block-item"
                                                >
                                                    <i data-feather="plus"></i>
                                                    {{ __('localization.document_types_create_document_fields_add_block') }}
                                                </button>
                                            </div>
                                        </div>

                                        <x-layout.document-fields-panel
                                            id="add-field"
                                            title-key="localization.document_types_create_document_fields"
                                            description-key="localization.document_types_create_document_fields_drag_field"
                                            search-placeholder-key="localization.document_types_create_document_fields_search_fields"
                                            create-field-title-key="localization.document_types_create_document_fields_create_field"
                                            create-custom-field-button-key="localization.document_types_create_document_fields_create_custom_field"
                                        />

                                        <x-layout.trash-block id="trash" />
                                    </div>
                                </div>
                            </x-tabs.content>

                            <x-tabs.content id="documents" :active="false">
                                <div class="document-fields px-0">
                                    <div class="row mx-0">
                                        <div
                                            class="fields-list col-12 col-lg-8 px-0 overflow-y-auto"
                                            style="height: 600px"
                                        >
                                            <ul
                                                class="sortableListDocuments sortableListStyle rounded list-group"
                                                data-type-block="documents"
                                                id="documents_fields"
                                            >
                                                <!-- Плейсхолдер всередині списку -->
                                                <li
                                                    id="documents_placeholder"
                                                    class="text-center p-2 text-muted px-5 border-dashed fw-bold bg-secondary-100 rounded"
                                                >
                                                    {{ __('localization.document_types.documents.fields-list.placeholder') }}
                                                </li>
                                            </ul>
                                        </div>

                                        <div id="add-field-documents" class="col-12 col-lg-4 ps-2">
                                            <h4 class="fw-bolder">
                                                {!! __('localization.document_types.documents.add-field.title') !!}
                                            </h4>
                                            <p>
                                                {!! __('localization.document_types.documents.add-field.desc') !!}
                                            </p>
                                            <div class="d-flex">
                                                <div class="input-group input-group-merge mb-1">
                                                    <span
                                                        class="input-group-text"
                                                        id="basic-addon-search2"
                                                    >
                                                        <i data-feather="search"></i>
                                                    </span>
                                                    <input
                                                        id="searchCreateFieldsDocuments"
                                                        type="text"
                                                        class="ps-1 form-control"
                                                        placeholder="{{ __('localization.document_types_create_document_fields_search_fields') }}"
                                                        aria-label="Search..."
                                                        aria-describedby="basic-addon-search2"
                                                    />
                                                </div>
                                                <div style="width: 38px"></div>
                                            </div>

                                            <x-pages.doc-types.list-group-wrapper>
                                                @foreach ($docTypes as $docType)
                                                    <x-pages.doc-types.document-item
                                                        :id="$docType->id"
                                                        :name="$docType->name"
                                                        :draggable="false"
                                                    />
                                                @endforeach
                                            </x-pages.doc-types.list-group-wrapper>
                                        </div>

                                        <x-layout.trash-block id="trash-documents" />
                                    </div>
                                </div>
                            </x-tabs.content>

                            <x-tabs.content id="action" :active="false">
                                <div class="document-fields px-0">
                                    <div class="row mx-0">
                                        <div
                                            class="fields-list col-12 col-lg-8 px-0 overflow-y-auto"
                                            style="height: 600px"
                                        >
                                            <div>
                                                @php
                                                    $actions = [
                                                        'edit' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'delete' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'copy' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'print' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                    ];
                                                @endphp

                                                <ul
                                                    class="sortableListAction sortableListStyle list-group"
                                                    data-type-block="action"
                                                    id="action_fields"
                                                >
                                                    @foreach ($actions as $actionKey => $action)
                                                        <x-action-item
                                                            :key="$actionKey"
                                                            :roles="$action['roles']"
                                                        />
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div id="add-field-action" class="col-12 col-lg-4 ps-2">
                                            <h4 class="fw-bolder">
                                                {!! __('localization.document_types.action.add-field.title') !!}
                                            </h4>
                                            <p>
                                                {!! __('localization.document_types.action.add-field.desc') !!}
                                            </p>
                                            <div class="d-flex">
                                                <div class="input-group input-group-merge mb-1">
                                                    <span
                                                        class="input-group-text"
                                                        id="basic-addon-search2"
                                                    >
                                                        <i data-feather="search"></i>
                                                    </span>
                                                    <input
                                                        id="searchCreateFieldsAction"
                                                        type="text"
                                                        class="ps-1 form-control"
                                                        placeholder="{{ __('localization.document_types_create_document_fields_search_fields') }}"
                                                        aria-label="Search..."
                                                        aria-describedby="basic-addon-search2"
                                                    />
                                                </div>
                                                <div style="width: 38px"></div>
                                            </div>

                                            <x-pages.doc-types.list-group-wrapper>
                                                @php
                                                    $actions = [
                                                        'edit' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'delete' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'copy' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'print' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'carrying_out' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                        'action' => ['roles' => ['admin' => true, 'storekeeper' => true, 'driver' => true, 'manager' => false]],
                                                    ];
                                                @endphp

                                                @foreach ($actions as $actionKey => $action)
                                                    <x-action-item
                                                        :key="$actionKey"
                                                        :roles="$action['roles']"
                                                        :draggable="false"
                                                    />
                                                @endforeach
                                            </x-pages.doc-types.list-group-wrapper>
                                        </div>

                                        <x-layout.trash-block id="action-trash" />
                                    </div>
                                </div>
                            </x-tabs.content>

                            <x-tabs.content id="task" :active="false">
                                @php
                                    $type = request()->get('document_kind', 'arrival');

                                    $taskSets = [
                                        // 1. Прихідний
                                        'arrival' => [['order' => 1, 'original' => 1, 'title' => 'Приймання товару', 'key' => 'goods_reception', 'type' => 'accept', 'fixedPosition' => null]],
                                        // 2. Розхідний
                                        'outcome' => [
                                            ['order' => 1, 'original' => 1, 'title' => 'Внутрішнє переміщення', 'key' => 'vnutrishnie-peremischennya', 'type' => 'move_replenishment', 'fixedPosition' => null],
                                            ['order' => 2, 'original' => 2, 'title' => 'Відбір', 'key' => 'vidbir', 'type' => 'pick', 'enabled' => true, 'fixedPosition' => 1],
                                            ['order' => 3, 'original' => 3, 'title' => 'Внутрішнє переміщення', 'key' => 'peremischennya-kontrol', 'type' => 'move_to_check', 'fixedPosition' => null],
                                            ['order' => 4, 'original' => 4, 'title' => 'Контроль', 'key' => 'kontrol', 'type' => 'check', 'fixedPosition' => null],
                                            ['order' => 5, 'original' => 5, 'title' => 'Внутрішнє переміщення', 'key' => 'rozpodil-konteyneriv', 'type' => 'move_sorting', 'fixedPosition' => null],
                                            ['order' => 6, 'original' => 6, 'title' => 'Внутрішнє переміщення', 'key' => 'zona-vidvantazhennya', 'type' => 'move_to_shipping', 'fixedPosition' => null],
                                            ['order' => 7, 'original' => 7, 'title' => 'Відвантаження', 'key' => 'vidvantazhennya', 'type' => 'ship', 'enabled' => true, 'fixedPosition' => 4],
                                        ],
                                        // 3. Внутрішній
                                        'inner' => [['order' => 1, 'original' => 1, 'title' => 'Внутрішнє переміщення', 'key' => 'vnutrishnie-peremischennya', 'type' => 'move_internal', 'fixedPosition' => null]],
                                        // 4. Нейтральний
                                        'neutral' => [],
                                    ];

                                    $tasks = $taskSets[$type] ?? [];
                                @endphp

                                <x-pages.doc-types.task-list :tasks="$tasks" />
                            </x-tabs.content>
                        @endslot
                    </x-tabs.group>
                </x-slot>
            </x-card.nested>

            @include('layouts.doc-type.custom-field-modal')
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script>
        var dictionaryList = {!! json_encode(\App\Helpers\DictionaryList::list()) !!};
        var doctypeFields = {!! $doctypeFields->toJson() !!};

        // Словники для зберігання останніх ідентифікаторів
        var lastIdDirectorySelect = {};
        var lastIdBlockDataParam = {};
        var lastIdDirectoryBlock = {};
        var lastIdParameterBtnShow = {};
        var lastIdParameterBlock = {};
        var lastIdAddItemParameter = {};
        var lastIdParameterList = {};
        var lastIdRemoveButtonParameters = {};
        var lastIdAddItemInDirectory = {};
        var lastIdRequiredDictionary = {};
        var lastIdRequiredIsNumberDictionary = {};

        var lastIdParameterInputParameterField = {};
    </script>
    {{-- <script>console.log(doctypeFields)</script> --}}

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('vendors/js/extensions/dragula.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script
        type="module"
        src="{{ asset('assets/js/entity/document-type/document-type.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/entity/document-type/configurator.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/entity/document-type/utils/tasks.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/entity/document-type/utils/custom-field-modal.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/entity/document-type/utils/accordion.js') }}"
    ></script>
    <script type="module">
        import { translateDocTypesName } from '{{ asset('assets/js/localization/document-type/translateDocTypesName.js') }}';

        translateDocTypesName('.translate-system-document-type');
    </script>
    <!-- END: Page JS-->

    <!-- Jquery UI start -->
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    ></script>
    <script src="{{ asset('vendors/js/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
    <!-- Jquery UI end -->
@endsection
