@props([
    'id' => 'add-field',
    'titleKey',
    'descriptionKey',
    'searchPlaceholderKey',
    'createFieldTitleKey',
    'createCustomFieldButtonKey',
])

<div id="{{ $id }}" class="col-12 col-lg-4 ps-2">
    <h4 class="fw-bolder">
        {{ __($titleKey) }}
    </h4>
    <p class="w-75">
        {!! __($descriptionKey) !!}
    </p>
    <div class="d-flex">
        <div class="input-group input-group-merge mb-1">
            <span class="input-group-text" id="basic-addon-search2">
                <i data-feather="search"></i>
            </span>
            <input
                id="searchCreateFields"
                type="text"
                class="ps-1 form-control"
                placeholder="{{ __($searchPlaceholderKey) }}"
                aria-label="Search..."
                aria-describedby="basic-addon-search2"
            />
        </div>
        <div style="width: 38px"></div>
    </div>

    <div class="pe-2 document-new-fields" style="height: 410px; overflow-y: scroll">
        <div class="mb-2 accordion-group-field">
            <div class="accordion-header-castom p-1 bg-white mb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <h6 class="m-0">
                            {{ __($createFieldTitleKey) }}
                        </h6>
                    </div>
                    <div class="d-flex">
                        <div>
                            <img
                                id="accordion-chevron"
                                width="16px"
                                src="{{ asset('assets/icons/entity/document-type/chevron-right.svg') }}"
                                alt="chevron"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-group-field-body d-none">
                <ul
                    class="arrTypeSystemFieldsCustom align-items-center row"
                    style="list-style-type: none; padding: 0"
                ></ul>
            </div>
        </div>

        <h6 class="py-1">
            {{ __($titleKey . '_text_fields') }}
        </h6>
        <ul class="arrTypeSystemFieldsListText" style="list-style-type: none; padding: 0"></ul>
        <ul class="arrTypeSystemFieldsListRange" style="list-style-type: none; padding: 0"></ul>

        <h6 class="py-1">
            {{ __($titleKey . '_date_time_fields') }}
        </h6>
        <ul class="arrTypeSystemFieldsListDate" style="list-style-type: none; padding: 0"></ul>
        <ul class="arrTypeSystemFieldsListDateRange" style="list-style-type: none; padding: 0"></ul>
        <ul class="arrTypeSystemFieldsListDateTime" style="list-style-type: none; padding: 0"></ul>
        <ul
            class="arrTypeSystemFieldsListDateTimeRange"
            style="list-style-type: none; padding: 0"
        ></ul>
        <ul class="arrTypeSystemFieldsListTimeRange" style="list-style-type: none; padding: 0"></ul>

        <h6 class="py-1">
            {{ __($titleKey . '_selection_fields') }}
        </h6>
        <ul class="arrTypeSystemFieldsListSelect" style="list-style-type: none; padding: 0"></ul>
        <ul class="arrTypeSystemFieldsLabel" style="list-style-type: none; padding: 0"></ul>

        <h6 class="py-1">
            {{ __($titleKey . '_other_fields') }}
        </h6>
        <ul class="arrTypeSystemFieldsListSwitch" style="list-style-type: none; padding: 0"></ul>
        <ul
            class="arrTypeSystemFieldsListUploadFile"
            style="list-style-type: none; padding: 0"
        ></ul>
        <ul class="arrTypeSystemFieldsListComment" style="list-style-type: none; padding: 0"></ul>
    </div>
    <div class="my-50">
        <button
            type="button"
            class="btn btn-flat-dark"
            data-bs-toggle="modal"
            data-bs-target="#customField"
        >
            {{ __($createCustomFieldButtonKey) }}
        </button>
    </div>
</div>
