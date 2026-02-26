@extends('layouts.admin')
@section('title', __('localization.container-register.index.title'))

@section('before-style')
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.base.css') }}"
        type="text/css"
    />
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css') }}"
        type="text/css"
    />
@endsection

@section('table-js')
    @include('layouts.table-scripts')
    <script
        type="module"
        src="{{ asset('assets/js/grid/container-register/container-register-table.js') }}"
    ></script>
@endsection

@section('content')
    @if ($containerRegisterCount)
        <x-layout.index-table-card
            :title="__('localization.container-register.index.container')"
            tableId="container-register-table"
        >
            <div class="d-flex gap-50">
                <x-modal.modal-trigger-button
                    id="print_button"
                    target="print-modal"
                    class="btn btn-outline-dark"
                    icon="printer"
                    iconStyle="mr-0"
                />

                <button
                    class="btn btn-primary d-flex align-items-center"
                    data-bs-toggle="modal"
                    data-bs-target="#create-modal"
                >
                    <img
                        class="plus-icon"
                        src="{{ asset('assets/icons/utils/plus.svg') }}"
                        alt="plus"
                    />
                    {{ __('localization.container-register.index.add_button') }}
                </button>
            </div>
        </x-layout.index-table-card>
    @else
        <x-layout.index-empty-message
            :title="__('localization.container-register.index.no_container')"
            :message="__('localization.container-register.index.create_soon')"
            :buttonText="__('localization.container-register.index.add_button')"
            buttonModalToggle="modal"
            buttonModalTarget="#create-modal"
        />
    @endif

    <x-modal.base id="create-modal" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <x-ui.section-card-title level="3" class="modal-title fw-bolder pb-1">
                    {{ __('localization.container-register.index.create_modal_title') }}
                </x-ui.section-card-title>
            </div>
        </x-slot>

        <x-slot name="body">
            <form class="row mx-0">
                <x-form.select
                    id="type_id"
                    name="type_id"
                    :label="__('localization.container-register.index.type_label')"
                    :placeholder="__('localization.container-register.index.type_label')"
                    data-dictionary="container"
                ></x-form.select>

                <x-form.input-text
                    id="count_container"
                    name="count_container"
                    :label="__('localization.container-register.index.count_label')"
                    type="number"
                    :placeholder="__('localization.container-register.index.count_placeholder')"
                />

                <x-ui.section-divider />

                <x-form.switch
                    id="іsPrint"
                    name="іsPrint"
                    :checked="true"
                    :label="__('localization.container-register.index.print_label')"
                    :inline="true"
                />

                <div class="col-6 px-0 mb-1 hidden d-md-block"></div>

                <div id="іsPrintBody" class="row mx-0 px-0">
                    <x-form.select
                        id="print_id"
                        name="print_id"
                        :label="__('localization.container-register.index.printer_label')"
                        :placeholder="__('localization.container-register.index.printer_label')"
                    >
                        <option value=""></option>
                        <option value="1">1</option>
                    </x-form.select>

                    <div class="col-12 col-md-6 mb-1 px-0 mb-1 hidden d-md-block"></div>
                </div>

                <div class="mt-1" id="base-error"></div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#create-modal"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.container-register.index.cancel_button') }}
            </button>
            <x-ui.action-button
                id="create"
                class="btn btn-primary"
                :text="__('localization.container-register.index.save_button')"
            />
        </x-slot>
    </x-modal.base>

    @if ($containerRegisterCount)
        <x-modal.base id="print-modal" size="modal-lg" style="max-width: 680px !important">
            <x-slot name="header">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <x-ui.section-card-title level="3" class="modal-title fw-bolder pb-1">
                        {{ __('localization.container-register.index.print_modal_title') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0 js-modal-form">
                    <x-form.select
                        id="type_id_print"
                        name="type_id_print"
                        :label="__('localization.container-register.index.type_label')"
                        :placeholder="__('localization.container-register.index.type_label')"
                        data-dictionary="container_type"
                    ></x-form.select>

                    <div class="col-6 px-0 mb-1 hidden d-md-block"></div>

                    <x-form.input-text
                        id="count"
                        name="count"
                        type="number"
                        :label="__('localization.container-register.index.count_to_print_label')"
                        :placeholder="__('localization.container-register.index.count_placeholder')"
                    />

                    <div class="col-12 col-md-6 align-items-center d-flex">
                        {{ __('localization.container-register.index.free_containers') }}:
                        <b>560</b>
                    </div>

                    <div class="mt-1" id="print-error"></div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button
                    type="button"
                    class="btn btn-link"
                    data-bs-target="#print-modal"
                    data-bs-toggle="modal"
                    data-dismiss="modal"
                >
                    {{ __('localization.container-register.index.cancel_button') }}
                </button>
                <x-ui.action-button
                    id="print"
                    disabled
                    class="btn btn-primary"
                    :text="__('localization.container-register.index.print_button')"
                />
            </x-slot>
        </x-modal.base>

        <!-- 🔹 Лоадер поверх -->
        <div
            id="print-loader"
            class="position-fixed top-0 start-0 d-none align-items-center justify-content-center bg-secondary-100 w-100 vh-100 bg-secondary-100"
            style="z-index: 10001"
        >
            <div class="spinner-border text-primary me-2" role="status"></div>
            <span class="fw-bold">
                {{ __('localization.container-register.print_modal.loader_text') }}
            </span>
        </div>
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#container-register-table'), '', 100, 150);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#container-register-table'));
    </script>

    <script type="module">
        import { toggleBlock } from '{{ asset('assets/js/utils/toggleBlock.js') }}';

        toggleBlock({
            checkboxId: 'іsPrint',
            targetId: 'іsPrintBody',
        });
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/container-register/container-register.js') }}"
    ></script>
@endsection
