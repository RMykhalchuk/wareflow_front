@extends("layouts.admin")
@section("title", __("localization.documents.view.title"))

@php
    $state = $document?->state ?? "task";
@endphp

@section("page-style")
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix("vendors/css/pickers/pickadate/pickadate.css")) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix("vendors/css/pickers/flatpickr/flatpickr.min.css")) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix("css/base/plugins/forms/pickers/form-flat-pickr.css")) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix("css/base/plugins/forms/pickers/form-pickadate.css")) }}"
    />
@endsection

@section("before-style")
    <link
        rel="stylesheet"
        href="{{ asset("assets/libs/jqwidget/jqwidgets/styles/jqx.base.css") }}"
        type="text/css"
    />
    <link
        rel="stylesheet"
        href="{{ asset("assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css") }}"
        type="text/css"
    />
@endsection

@section("table-js")
    @include("layouts.table-scripts")

    @if ($state === "task")
        <script type="module" src="{{ asset("assets/js/grid/document/tasks-table.js") }}"></script>
    @endif

    <script
        type="module"
        src="{{ asset("assets/js/grid/document/formed-containers-table.js") }}"
    ></script>

    @if ($documentType->kind === "arrival")
        <script
            type="module"
            src="{{ asset("assets/js/grid/document/arrival/preview-document-sku-table.js") }}"
        ></script>

        <script
            type="module"
            src="{{ asset("assets/js/grid/document/arrival/leftovers-table.js") }}"
        ></script>
    @endif

    @if ($documentType->kind === "outcome")
        <script
            type="module"
            src="{{ asset("assets/js/grid/document/outcome/preview-document-sku-table.js") }}"
        ></script>

        <script
            type="module"
            src="{{ asset("assets/js/grid/document/outcome/leftovers-table.js") }}"
        ></script>
    @endif
@endsection

@section("content")
    <x-layout.container
        fluid
        id="document-container"
        data-type="{{$documentType->kind}}"
        data-id="{{$document->id}}"
        data-task-id="{{$document->id}}"
        data-status="{{$document->status->key}}"
    >
        <x-slot:header>
            @include(
                "panels.breadcrumb",
                [
                    "options" => [
                        [
                            "url" => "/document",
                            "name" => __(
                                "localization.documents.view.breadcrumb.documents",
                            ),
                        ],
                        [
                            "url" =>
                                "/document/table/" .
                                $documentType->id .
                                "?warehouse_id=" .
                                $document->warehouse_id,
                            "name" => $documentType->name,
                        ],
                        [
                            "name" => __("localization.documents.view.breadcrumb.view"),
                            "name2" => $documentType->name,
                            "name3" => " №" . $documentType->local_id,
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                {{-- Іконка тільки для created --}}
                @if ($document->status->key === "created")
                    <x-ui.icon-dropdown id="header-dropdown" menuClass="px-1">
                        {{-- <x-ui.dropdown-item --}}
                        {{-- href="/document/{{ $document->id }}/edit" --}}
                        {{-- :text="__('localization.documents.view.actions.edit')" --}}
                        {{-- /> --}}
                        <x-ui.dropdown-item
                            href="#"
                            id="cancelExecution"
                            :text="__('localization.documents.view.actions.cancel_execution')"
                        />
                    </x-ui.icon-dropdown>

                    <div class="vr mx-0 my-25 bg-secondary-subtle"></div>
                @endif

                {{-- Кнопки для created + process --}}
                @if (in_array($document->status->key, ["created", "process"]))
                    <div class="btn-group">
                        {{-- Обидві кнопки існують, одна прихована --}}
                        <button
                            id="create_task"
                            class="btn btn-primary @if($state !== 'task') d-none @endif"
                        >
                            {{ __("localization.documents.view.action_with_document.task") }}
                        </button>

                        <button
                            id="process_manually"
                            class="btn rounded-start btn-primary @if($state !== 'manual') d-none @endif"
                        >
                            {{ __("localization.documents.view.action_with_document.manual") }}
                        </button>

                        {{-- Стрілка для вибору --}}
                        <button
                            type="button"
                            class="btn btn-primary dropdown-toggle dropdown-toggle-split no-caret py-1 px-2"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="
                                --bs-dropdown-toggle-after-bg: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;);
                                background-image: var(--bs-dropdown-toggle-after-bg);
                                background-repeat: no-repeat;
                                background-position: center;
                                background-size: 16px;
                            "
                        >
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>

                        <ul class="dropdown-menu">
                            <li>
                                <a
                                    class="dropdown-item js-doc-dropdown-item"
                                    href="#"
                                    data-type="task"
                                >
                                    {{ __("localization.documents.view.action_with_document.task") }}
                                </a>
                            </li>
                            <li>
                                <a
                                    class="dropdown-item js-doc-dropdown-item"
                                    href="#"
                                    data-type="manual"
                                >
                                    {{ __("localization.documents.view.action_with_document.manual") }}
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <div class="d-flex gap-1 align-items-center justify-content-between my-2">
                <div class="d-flex gap-1 align-items-center">
                    <x-page-title
                        class="mt-0"
                        :title="'<span class=\'x-page-title-document-name\'>' . $documentType->name . '</span>'.' №' . $document->local_id"
                    />

                    <span
                        class="badge fw-bold @if ($document->status->key === "created")
                            badge-light-primary
                        @elseif ($document->status->key === "process")
                            badge-light-success
                        @elseif ($document->status->key === "done")
                            badge-light-secondary
                        @endif"
                        style="color: #000 !important"
                    >
                        {{ $document->status->name }}
                    </span>
                </div>

                <div class="d-flex gap-25 flex-column align-items-end">
                    <div class="d-flex align-items-center gap-50 font-small-2">
                        <i data-feather="calendar"></i>
                        <span>{{ __("localization.documents.view.created_at") }}</span>
                        <span class="fw-bold">
                            {{ $document->created_at->format("d/m/Y, H:i") }}
                        </span>
                    </div>

                    <div class="align-items-center gap-50 font-small-2">
                        <i data-feather="user"></i>
                        <span>{{ __("localization.documents.view.author") }}</span>
                        <span class="fw-bold">
                            {{ $document->log->user->surname . " " . $document->log->user->name }}
                        </span>
                    </div>
                </div>
            </div>

            <x-tabs.group class="" navClass="css-custom-nav-tabs">
                @slot("items")
                    <x-tabs.item
                        id="contents"
                        :title="__('localization.documents.view.tabs.contents')"
                        :active="true"
                    />

                    @if ($state === "task")
                        <x-tabs.item
                            id="tasks"
                            :title="__('localization.documents.view.tabs.tasks')"
                            {{-- :disabled="true" --}}
                        />
                    @endif

                    <x-tabs.item
                        id="formedContainers"
                        :title="__('localization.documents.view.tabs.formed_containers')"
                        :disabled="$document->status->key === 'created'"
                    />
                @endslot

                @slot("content")
                    <x-tabs.content id="contents" :active="true">
                        <x-card.nested>
                            <x-slot:header>
                                <x-section-title>
                                    {{ __("localization.documents.view.data_info_title") }}
                                </x-section-title>
                            </x-slot>

                            <x-slot:body>
                                @php
                                    $settingsFieldsStructure = $documentStructure->settings["fields"]["header"];
                                    $settingsFields = json_decode($document->documentType->settings, true)["fields"]["header"];
                                    $documentData = $document->data()["header"];
                                @endphp

                                {{-- <script>console.log(@json($settingsFields))</script> --}}
                                {{-- <script>console.log(@json($documentData))</script> --}}

                                <x-pages.document.document-fields-columns
                                    :fields="$settingsFieldsStructure"
                                    :document-data="$documentData"
                                    :document-type="$documentType"
                                />

                                @if ($settingsFields)
                                    <div class="px-1">
                                        <hr class="my-1" />
                                    </div>

                                    <x-pages.document.document-fields-columns
                                        :fields="$settingsFields"
                                        :document-data="$documentData"
                                        :document-type="$documentType"
                                    />
                                @endif

                                <div class="px-1">
                                    <hr class="my-1" />
                                </div>

                                <div class="row">
                                    {{-- ERP fields --}}
                                    <div class="col-6">
                                        <div class="d-flex gap-1 mb-1">
                                            <div class="f-15 col-6">
                                                {{ __("localization.documents.view.document_erp_number") }}
                                            </div>
                                            <div class="fw-6 col-6">-</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="d-flex gap-1 mb-1">
                                            <div class="f-15 col-6">
                                                {{ __("localization.documents.view.document_erp_id") }}
                                            </div>
                                            <div class="fw-6 col-6">-</div>
                                        </div>
                                    </div>
                                </div>

                                <div id="main-data-message"></div>
                            </x-slot>
                        </x-card.nested>

                        @for ($i=0;$i<count(json_decode($document->documentType->settings,true)['custom_blocks']);$i++)
                            <x-card.nested>
                                <x-slot:header>
                                    <x-section-title>
                                        @php
                                            $blockName = $document->documentType->settings()["block_names"][$i];
                                            $translatedBlockKey = Str::slug($blockName, "_");

                                            $localizationBlockKey = "localization.documents.fields." . $documentType->kind . ".title." . $translatedBlockKey;
                                        @endphp

                                        {{-- @dd($localizationBlockKey) --}}
                                        {{ Lang::has($localizationBlockKey) ? __($localizationBlockKey) : $blockName }}
                                    </x-section-title>
                                </x-slot>

                                <x-slot:body>
                                    <x-pages.document.document-fields-columns
                                        :fields="json_decode($document->documentType->settings, true)['custom_blocks'][$i]"
                                        :document-data="$document->data()['custom_blocks'][$i]"
                                        :document-type="$document->documentType"
                                    />
                                </x-slot>
                            </x-card.nested>
                        @endfor

                        @if ($documentType->kind === "arrival" || $documentType->kind === "outcome")
                            <x-layout.index-table-card
                                :title="__('localization.documents.view.sku_title')"
                                tableId="previewSkuDataTable"
                                idOne="settingTable-sku"
                                idTwo="changeFonts-sku"
                                idThree="changeCol-sku"
                                idFour="jqxlistbox-sku"
                                class="mx-0 justify-content-between"
                            />
                        @endif
                    </x-tabs.content>

                    @if ($state === "task")
                        <x-tabs.content id="tasks" :active="false">
                            @if ($document->status->key === "created")
                                <x-card.nested>
                                    <x-slot:header>
                                        <x-section-title>
                                            {{ __("localization.documents.view.tabs.tasks") }}
                                        </x-section-title>
                                    </x-slot>

                                    <x-slot:body>
                                        <hr />
                                        <div
                                            class="d-block px-1 mt-1 gap-1 bg-secondary-100 d-flex flex-column justify-content-center rounded align-items-center text-dark height-200 p-1"
                                        >
                                            <h5 class="text-dark text-center">
                                                {{ __("localization.documents.view.tabs.tasks_placeholder") }}
                                            </h5>
                                        </div>
                                    </x-slot>
                                </x-card.nested>
                            @else
                                <x-layout.index-table-card
                                    :title="__('localization.documents.view.tabs.tasks')"
                                    tableId="tasks-table"
                                    idOne="settingTable-tasks"
                                    idTwo="changeFonts-tasks"
                                    idThree="changeCol-tasks"
                                    idFour="jqxlistbox-tasks"
                                    class="mx-0"
                                />
                            @endif
                        </x-tabs.content>
                    @endif

                    <x-tabs.content id="formedContainers" :active="false">
                        @if ($document->status->key !== "done")
                            <x-card.nested>
                                <x-slot:header>
                                    <x-section-title>
                                        {{ __("localization.documents.view.tabs.containers") }}
                                    </x-section-title>
                                </x-slot>

                                <x-slot:body>
                                    <hr />
                                    <div
                                        class="d-block px-1 mt-1 gap-1 bg-secondary-100 d-flex flex-column justify-content-center rounded align-items-center text-dark height-200 p-1"
                                    >
                                        <h5 class="text-dark text-center">
                                            {{ __("localization.documents.view.tabs.containers_placeholder") }}
                                        </h5>
                                    </div>
                                </x-slot>
                            </x-card.nested>
                        @else
                            <x-layout.index-table-card
                                :title="__('localization.documents.view.tabs.containers')"
                                tableId="formed-containers-table"
                                idOne="settingTable-formedContainers"
                                idTwo="changeFonts-formedContainers"
                                idThree="changeCol-formedContainers"
                                idFour="jqxlistbox-formedContainers"
                                class="mx-0"
                            />
                        @endif
                    </x-tabs.content>
                @endslot
            </x-tabs.group>
        </x-slot>
    </x-layout.container>

    @if ($documentType->kind === "arrival")
        <x-modal.base id="target" class="js-table-popover-modal" size="modal-fullscreen">
            <x-slot name="header">
                <div class="d-flex flex-grow-1 justify-content-between gap-1 align-items-center">
                    <div class="d-flex flex-column flex-grow-1 gap-50 col-4">
                        <div class="fw-bold">
                            {{ __("localization.inventory.view.modals.leftovers") }}
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <!-- Назва комірки -->
                            <div
                                id="leftovers-name"
                                class="mb-0 fw-bolder fs-4 w-auto"
                                data-goods-id=""
                                data-quantity="0"
                            >
                                Товар -
                            </div>
                            <div class="d-flex flex-column w-50 align-items-center gap-75">
                                <div class="d-flex align-items-center w-100">
                                    <div class="progress w-75 me-50" style="height: 6px">
                                        <div
                                            id="leftovers-progress-bar"
                                            class="progress-bar bg-warning"
                                            role="progressbar"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: 0%"
                                        ></div>
                                    </div>
                                    <div class="small w-25">
                                        <span id="leftovers-current">0</span>
                                        /
                                        <span id="leftovers-max">0</span>
                                        <span id="leftovers-unit">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <button
                            disabled
                            type="button"
                            class="btn d-none btn-primary"
                            id="target_submit"
                        >
                            {{ __("localization.inventory.view.modals.buttons.confirm") }}
                        </button>
                        <div class="vr mx-0 my-25 bg-secondary-subtle d-none"></div>

                        <button
                            class="btn btn-flat-secondary p-1"
                            data-bs-dismiss="modal"
                            aria-label="{{ __("localization.inventory.view.modals.buttons.cancel") }}"
                        >
                            <i data-feather="x"></i>
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="card-grid" style="position: relative">
                    @include(
                        "layouts.table-setting",
                        [
                            "idOne" => "settingTable-leftovers",
                            "idTwo" => "changeFonts-leftovers",
                            "idThree" => "changeCol-leftovers",
                            "idFour" => "jqxlistbox-leftovers",
                        ]
                    )

                    <!-- Таблиця -->
                    <div class="table-block d-none" id="leftoversDataTable"></div>
                </div>

                <!-- Порожній блок -->
                <div
                    id="empty-cell-block"
                    class="px-1 bg-secondary-100 d-flex flex-grow-1 flex-column gap-1 justify-content-center rounded align-items-center h-100 text-dark p-1 d-none"
                >
                    <h3 id="placeholder_leftovers" class="d-block">Додати залишок</h3>

                    <x-modal.modal-trigger-button
                        id="add_leftovers_button_empty"
                        target="add_leftovers"
                        class="btn btn-outline-secondary d-block"
                        :text="'Додати'"
                        icon="plus"
                    />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-modal.modal-trigger-button
                    id="add_leftovers_button_footer"
                    target="add_leftovers"
                    class="btn btn-outline-secondary d-block"
                    :text="__('localization.inventory.view.modals.add_leftovers_button')"
                    icon="plus"
                />
            </x-slot>
        </x-modal.base>

        <x-modal.base id="add_leftovers" size="modal-fullscreen">
            <x-slot name="header">
                <div
                    class="d-flex px-50 flex-grow-1 justify-content-center position-relative gap-1 align-items-center"
                >
                    <x-ui.section-card-title level="2" class="modal-title">
                        {{ __("localization.inventory.view.modals.add_leftovers.title") }}
                    </x-ui.section-card-title>
                    <button
                        class="btn btn-flat-secondary p-1 position-absolute end-0"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i data-feather="x"></i>
                    </button>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0">
                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __("localization.inventory.view.modals.add_leftovers.product_params") }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="goods_id"
                        name="goods_id"
                        :label="__('localization.leftovers.form.goods')"
                        :placeholder="__('localization.leftovers.form.goods_placeholder')"
                        class="col-12 mb-1 d-none"
                    />

                    <x-form.input-text
                        id="batch"
                        name="batch"
                        :label="__('localization.inventory.view.modals.add_leftovers.batch.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.batch.placeholder')"
                        type="number"
                    />

                    <x-form.select
                        id="condition"
                        name="condition"
                        :label="__('localization.inventory.view.modals.add_leftovers.condition.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.condition.placeholder')"
                    >
                        <option value="false">
                            {{ __("localization.inventory.view.modals.add_leftovers.condition.option_dmg") }}
                        </option>
                        <option value="true">
                            {{ __("localization.inventory.view.modals.add_leftovers.condition.option_no_dmg") }}
                        </option>
                    </x-form.select>

                    <x-form.select
                        id="expiration_term"
                        name="expiration_term"
                        :label="__('localization.inventory.view.modals.add_leftovers.expiration.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.expiration.placeholder')"
                        data-dependent="goods_id"
                        data-dependent-param="goods_id"
                        data-dictionary-base="goods-expiration"
                        class="col-6 mb-1"
                    />

                    <x-form.input-group-wrapper wrapperClass="col-6 px-0 mb-1">
                        <x-form.date-input
                            id="manufacture_date"
                            name="manufacture_date"
                            :label="__('localization.inventory.view.modals.add_leftovers.manufacture_date')"
                            :placeholder="__('localization.inventory.view.modals.add_leftovers.manufacture_date')"
                            :required
                            class="col-6 mb-1 mb-md-0 position-relative"
                        />

                        <x-form.date-input
                            id="bb_date"
                            name="bb_date"
                            :label="__('localization.inventory.view.modals.add_leftovers.bb_date')"
                            :placeholder="__('localization.inventory.view.modals.add_leftovers.bb_date')"
                            :required
                            class="col-6 mb-1 mb-md-0 position-relative"
                        />
                    </x-form.input-group-wrapper>

                    <x-ui.section-divider />

                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __("localization.inventory.view.modals.add_leftovers.quantity_section") }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="packages_id"
                        name="packages_id"
                        :label="__('localization.inventory.view.modals.add_leftovers.packaging.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.packaging.placeholder')"
                        data-dependent="goods_id"
                        data-dependent-param="goods_id"
                        data-dictionary-base="packages"
                    />

                    <x-form.input-text
                        id="quantity"
                        name="quantity"
                        :label="__('localization.inventory.view.modals.add_leftovers.quantity.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.quantity.placeholder')"
                        type="number"
                        min="1"
                        oninput="validatePositive(this)"
                        required
                    />

                    <x-ui.section-divider />

                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __("localization.inventory.view.modals.add_leftovers.placement") }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="container_registers_id"
                        name="container_registers_id"
                        :label="__('localization.inventory.view.modals.add_leftovers.container.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.container.placeholder')"
                        data-dictionary="container_registers"
                        class="col-12 mb-1"
                    />

                    <div class="mt-1" id="add_leftovers_error_msg"></div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">
                    {{ __("localization.inventory.view.modals.buttons.cancel") }}
                </button>
                <button type="button" class="btn btn-primary" id="add_leftovers_submit">
                    {{ __("localization.inventory.view.modals.buttons.confirm") }}
                </button>
            </x-slot>
        </x-modal.base>

        <x-modal.base id="edit_leftovers" size="modal-fullscreen">
            <x-slot name="header">
                <div
                    class="d-flex px-50 flex-grow-1 justify-content-center position-relative gap-1 align-items-center"
                >
                    <x-ui.section-card-title level="2" class="modal-title">
                        {{ __("localization.inventory.view.modals.edit_leftovers.title") }}
                    </x-ui.section-card-title>
                    <button
                        class="btn btn-flat-secondary p-1 position-absolute end-0"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i data-feather="x"></i>
                    </button>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0">
                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __("localization.inventory.view.modals.add_leftovers.product_params") }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="edit_goods_id"
                        name="edit_goods_id"
                        :label="__('localization.leftovers.form.goods')"
                        :placeholder="__('localization.leftovers.form.goods_placeholder')"
                        class="col-12 mb-1 d-none"
                    />

                    <x-form.input-text
                        id="edit_batch"
                        name="edit_batch"
                        :label="__('localization.inventory.view.modals.add_leftovers.batch.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.batch.placeholder')"
                        type="number"
                    />

                    <x-form.select
                        id="edit_condition"
                        name="edit_condition"
                        :label="__('localization.inventory.view.modals.add_leftovers.condition.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.condition.placeholder')"
                    >
                        <option value="false">
                            {{ __("localization.inventory.view.modals.add_leftovers.condition.option_dmg") }}
                        </option>
                        <option value="true">
                            {{ __("localization.inventory.view.modals.add_leftovers.condition.option_no_dmg") }}
                        </option>
                    </x-form.select>

                    <x-form.select
                        id="edit_expiration_term"
                        name="edit_expiration_term"
                        :label="__('localization.inventory.view.modals.add_leftovers.expiration.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.expiration.placeholder')"
                        data-dependent="goods_id"
                        data-dependent-param="goods_id"
                        data-dictionary-base="goods-expiration"
                        class="col-6 mb-1"
                    />

                    <x-form.input-group-wrapper wrapperClass="col-6 px-0 mb-1">
                        <x-form.date-input
                            id="edit_manufacture_date"
                            name="edit_manufacture_date"
                            :label="__('localization.inventory.view.modals.add_leftovers.manufacture_date')"
                            :placeholder="__('localization.inventory.view.modals.add_leftovers.manufacture_date')"
                            :required
                            class="col-6 mb-1 mb-md-0 position-relative"
                        />

                        <x-form.date-input
                            id="edit_bb_date"
                            name="edit_bb_date"
                            :label="__('localization.inventory.view.modals.add_leftovers.bb_date')"
                            :placeholder="__('localization.inventory.view.modals.add_leftovers.bb_date')"
                            :required
                            class="col-6 mb-1 mb-md-0 position-relative"
                        />
                    </x-form.input-group-wrapper>

                    <x-ui.section-divider />

                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __("localization.inventory.view.modals.add_leftovers.quantity_section") }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="edit_packages_id"
                        name="edit_packages_id"
                        :label="__('localization.inventory.view.modals.add_leftovers.packaging.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.packaging.placeholder')"
                        data-dependent="edit_goods_id"
                        data-dependent-param="goods_id"
                        data-dictionary-base="packages"
                    />

                    <x-form.input-text
                        id="edit_quantity"
                        name="edit_quantity"
                        :label="__('localization.inventory.view.modals.add_leftovers.quantity.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.quantity.placeholder')"
                        type="number"
                        min="1"
                        oninput="validatePositive(this)"
                        required
                    />

                    <x-ui.section-divider />

                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __("localization.inventory.view.modals.add_leftovers.placement") }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="edit_container_registers_id"
                        name="edit_container_registers_id"
                        :label="__('localization.inventory.view.modals.add_leftovers.container.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.container.placeholder')"
                        data-dictionary="container_registers"
                        class="col-12 mb-1"
                    />

                    <div class="mt-1" id="edit_leftovers_error_msg"></div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">
                    {{ __("localization.inventory.view.modals.buttons.cancel") }}
                </button>
                <button type="button" class="btn btn-primary" id="edit_leftovers_submit">
                    {{ __("localization.inventory.view.modals.buttons.confirm") }}
                </button>
            </x-slot>
        </x-modal.base>
    @endif

    @if ($documentType->kind === "outcome")
        <x-modal.base id="target" class="js-table-popover-modal" size="modal-fullscreen">
            <x-slot name="header">
                <div class="d-flex flex-grow-1 justify-content-between gap-1 align-items-center">
                    <div class="d-flex flex-column flex-grow-1 gap-50 col-4">
                        <div class="fw-bold">
                            {{ __("localization.inventory.view.modals.leftovers") }}
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <!-- Назва комірки -->
                            <div
                                id="leftovers-name"
                                class="mb-0 fw-bolder fs-4 w-auto"
                                data-goods-id=""
                                data-quantity="0"
                            >
                                Товар -
                            </div>

                            <div class="d-flex flex-column w-50 align-items-center gap-75">
                                <div class="d-flex align-items-center w-100">
                                    <div class="progress w-75 me-50" style="height: 6px">
                                        <div
                                            id="leftovers-progress-bar"
                                            class="progress-bar bg-warning"
                                            role="progressbar"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: 0%"
                                        ></div>
                                    </div>
                                    <div class="small w-25">
                                        <span id="leftovers-current">0</span>
                                        /
                                        <span id="leftovers-max">0</span>
                                        <span id="leftovers-unit">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <button
                            disabled
                            type="button"
                            class="btn d-none btn-primary"
                            id="target_submit"
                        >
                            {{ __("localization.inventory.view.modals.buttons.confirm") }}
                        </button>
                        <div class="vr mx-0 my-25 bg-secondary-subtle d-none"></div>

                        <button
                            class="btn btn-flat-secondary p-1"
                            data-bs-dismiss="modal"
                            aria-label="{{ __("localization.inventory.view.modals.buttons.cancel") }}"
                        >
                            <i data-feather="x"></i>
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="card-grid" style="position: relative">
                    @include(
                        "layouts.table-setting",
                        [
                            "idOne" => "settingTable-leftovers",
                            "idTwo" => "changeFonts-leftovers",
                            "idThree" => "changeCol-leftovers",
                            "idFour" => "jqxlistbox-leftovers",
                        ]
                    )

                    <!-- Таблиця -->
                    <div class="table-block d-none" id="leftoversDataTable"></div>
                </div>

                <!-- Порожній блок -->
                <div
                    id="empty-cell-block"
                    class="px-1 bg-secondary-100 d-flex flex-grow-1 flex-column gap-1 justify-content-center rounded align-items-center h-100 text-dark p-1 d-none"
                >
                    <h3 id="placeholder_leftovers" class="d-block">Немає залишків</h3>
                </div>
            </x-slot>
        </x-modal.base>
    @endif
@endsection

@section("page-script")
    <script src="{{ asset("vendors/js/ui/jquery.sticky.js") }}"></script>
    <script src="{{ asset("vendors/js/pickers/pickadate/picker.js") }}"></script>
    <script src="{{ asset("vendors/js/pickers/pickadate/picker.time.js") }}"></script>
    <script src="{{ asset("vendors/js/pickers/flatpickr/flatpickr.min.js") }}"></script>
    <script src="{{ asset("js/scripts/forms/pickers/form-pickers.js") }}"></script>
    <script src="{{ asset("vendors/js/pickers/flatpickr/l10n/uk.js") }}"></script>

    <script>
        let activity_state = @json($document->state);
        // console.log(activity_state);
    </script>

    <script type="module">
        const table = $('#sku-table');

        window.tableData = @json($document->getSkuTableInfo());
    </script>

    <script type="module">
        import { tableSetting } from '{{ asset("assets/js/grid/components/table-setting.js") }}';

        tableSetting($('#previewSkuDataTable'), '-sku', 150, 200);
        tableSetting($('#tasks-table'), '-tasks', 100, 150);
        tableSetting($('#formed-containers-table'), '-formedContainers', 100, 150);
        tableSetting($('#leftoversDataTable'), '-leftovers', 50, 100);
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset("assets/js/utils/offCanvasByBorder.js") }}';

        offCanvasByBorder($('#previewSkuDataTable'), '-sku');
        offCanvasByBorder($('#tasks-table'), '-tasks');
        offCanvasByBorder($('#formed-containers-table'), '-formedContainers');
        offCanvasByBorder($('#leftoversDataTable'), '-leftovers');
    </script>

    <script type="module">
        import { translateDocTypesName } from '{{ asset("assets/js/localization/document-type/translateDocTypesName.js") }}';

        translateDocTypesName('.breadcrumb-item-name-active-js');
        translateDocTypesName('.breadcrumb-item-name-no-active-js');
        translateDocTypesName('.js-document-data-info-header-title');
        translateDocTypesName('.js-tabs-name-system-document');
        translateDocTypesName('.x-page-title-document-name');
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            flatpickr('.flatpickr-basic', {
                dateFormat: 'Y-m-d',
                locale: lang,
            });
        });
    </script>

    <script
        type="module"
        src="{{ asset("assets/js/utils/dictionary/selectDictionaryRelated.js") }}"
    ></script>

    @if ($documentType->kind === "arrival")
        <script
            type="module"
            src="{{ asset("assets/js/entity/document/document-leftovers.js") }}"
        ></script>
    @endif

    @if ($documentType->kind === "outcome")
        <script
            type="module"
            src="{{ asset("assets/js/entity/document/outcome/document-leftovers.js") }}"
        ></script>
    @endif

    <script>
        // 1️⃣ Початкові дані від PHP
        window.progressData = <?= json_encode($progressData) ?>;
        {{-- console.log(<?= json_encode($progressData) ?>); --}};
    </script>

    <script
        type="module"
        src="{{ asset("assets/js/entity/document/document-actions/create-task.js") }}"
    ></script>

    <script
        type="module"
        src="{{ asset("assets/js/entity/document/document-actions/process-manually.js") }}"
    ></script>

    <script src="{{ asset("assets/js/entity/leftovers/utils/expiration-calculator.js") }}"></script>

    {{-- todo придумати щось з табами --}}
    {{-- <script type="module"> --}}
    {{-- import { sendRequestBase } from '{{ asset('assets/js/utils/sendRequestBase.js') }}'; --}}
    {{-- import { getCurrentLocaleFromUrl } from '{{ asset('assets/js/utils/getCurrentLocaleFromUrl.js') }}'; --}}

    {{-- const document_id = $('#document-container').data('id'); --}}
    {{-- const csrf = document.querySelector('meta[name="csrf-token"]').content; --}}
    {{-- const locale = getCurrentLocaleFromUrl(); --}}

    {{-- const url = --}}
    {{-- locale === 'en' --}}
    {{-- ? `` // без префіксу --}}
    {{-- : `${locale}/`; --}}

    {{-- document.addEventListener('DOMContentLoaded', function () { --}}
    {{-- const tabs = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]'); --}}
    {{-- const url = new URL(window.location); --}}

    {{-- // 1️⃣ Якщо у посиланні вже є ?tab=... --}}
    {{-- const currentTab = url.searchParams.get('tab'); --}}
    {{-- if (currentTab) { --}}
    {{-- const tabElement = document.querySelector(`.nav-link[href="#${currentTab}"]`); --}}
    {{-- if (tabElement) { --}}
    {{-- const bsTab = new bootstrap.Tab(tabElement); --}}
    {{-- bsTab.show(); --}}
    {{-- } --}}
    {{-- } --}}

    {{-- // 2️⃣ При перемиканні таби — оновлюємо URL і надсилаємо запит --}}
    {{-- tabs.forEach((tab) => { --}}
    {{-- tab.addEventListener('shown.bs.tab', async (event) => { --}}
    {{-- const tabId = event.target.getAttribute('href').replace('#', ''); --}}
    {{-- url.searchParams.set('tab', tabId); --}}
    {{-- window.history.replaceState({}, '', url); --}}

    {{-- // ✅ Відправляємо на сервер, щоб оновити активну табу --}}
    {{-- await sendQuickUpdate('active_tab', tabId); --}}
    {{-- }); --}}
    {{-- }); --}}
    {{-- }); --}}

    {{-- // 🔹 Функція для швидкого оновлення JSON (без зміни інших даних) --}}
    {{-- async function sendQuickUpdate(flagName, flagValue) { --}}
    {{-- try { --}}
    {{-- const formData = new FormData(); --}}
    {{-- const updateData = { [flagName]: flagValue }; --}}

    {{-- formData.append('_token', csrf); --}}
    {{-- formData.append('_method', 'PUT'); --}}
    {{-- formData.append('data', JSON.stringify(updateData)); --}}
    {{-- formData.append('status_id', 1); --}}

    {{-- await sendRequestBase(`${url}document/` + document_id, formData, false, function () { --}}
    {{-- console.log(`✅ Поле "${flagName}" оновлено значенням "${flagValue}"`); --}}
    {{-- }); --}}
    {{-- } catch (error) { --}}
    {{-- console.error('❌ Помилка при оновленні поля:', error); --}}
    {{-- } --}}
    {{-- } --}}
    {{-- </script> --}}
@endsection
