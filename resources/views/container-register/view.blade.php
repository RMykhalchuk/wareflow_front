@extends('layouts.admin')
@section('title', __('localization.container-register.view.title'))

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

@section('page-style')
    
@endsection

@section('table-js')
    @include('layouts.table-scripts')
    <script
        type="module"
        src="{{ asset('assets/js/grid/container-register/view-details-table.js') }}"
    ></script>
@endsection

@php
    $statusId = $containerRegister->status['value'];

    $badgeClass = match ($statusId) {
        1 => 'bg-success',
        2 => 'bg-warning',
        3 => 'bg-secondary',
        default => 'bg-danger',
    };
@endphp

@section('content')
    <x-layout.container id="container-register-container" data-id="{{ $containerRegister->id }}">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/container-register',
                            'name' => __('localization.container-register.index.title'),
                        ],
                        [
                            'name' => $containerRegister->code,
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <button id="view-print" class="btn btn-flat-dark d-flex align-items-center">
                    <i data-feather="printer"></i>
                </button>

                <div class="vr mx-0 my-100 bg-secondary-subtle"></div>

                <x-ui.icon-dropdown
                    icon="chevron-down"
                    id="header-dropdown"
                    :text="__('localization.container-register.view.logs')"
                    menuClass="d-flex flex-column justify-content-center"
                >
                    <x-ui.dropdown-item
                        href="#"
                        icon="info"
                        :text="__('localization.container-register.view.header_actions_1')"
                    />
                </x-ui.icon-dropdown>

                <div class="vr mx-0 my-100 bg-secondary-subtle"></div>

                <x-ui.icon-dropdown
                    id="header-dropdown"
                    menuClass="d-flex flex-column justify-content-center px-1"
                >
                    <x-modal.modal-trigger-button
                        id="cancel_button"
                        target="delete-modal"
                        :disabled="count($containerRegister->leftovers) > 0 || $statusId === 3"
                        class="btn btn-flat-danger"
                        :text="__('localization.container-register.view.modal_delete_confirm')"
                    />
                </x-ui.icon-dropdown>
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested id="jsCopyEventScope">
                <x-slot:header>
                    <div class="d-flex flex-column px-1">
                        <x-ui.section-card-title level="4">
                            {{ $containerRegister->code }}
                        </x-ui.section-card-title>

                        <p class="mb-0">
                            {{ $containerRegister->container->type->name }}
                        </p>
                    </div>
                </x-slot>

                <x-slot:body>
                    <x-ui.section-divider class="mt-1 mb-2" />

                    <x-ui.col-row-wrapper>
                        <x-ui.section-card-title level="5">
                            {{ __('localization.container-register.view.basic_data') }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <x-card.card-data-row
                                :label="__('localization.container-register.view.status')"
                            >
                                <span class="badge {{ $badgeClass }} py-50 px-1">
                                    {{ $containerRegister->status['label'] ?? '' }}
                                </span>
                            </x-card.card-data-row>

                            @if ($containerRegister->cell)
                                <x-card.card-data-row
                                    :label="__('localization.container-register.view.placement')"
                                    :value="$containerRegister->cell->allocationToString()"
                                />
                            @endif

                            @php
                                [$loadWeight, $maxWeight] = $containerRegister->loadWeightToArray();
                                $percent = $maxWeight ? round(($loadWeight / $maxWeight) * 100) : 0;
                            @endphp

                            @if ($loadWeight || $maxWeight)
                                <x-card.card-data-row
                                    :label="__('localization.container-register.view.loading')"
                                    :copyButton="false"
                                >
                                    <div
                                        class="d-flex flex-row justify-content-between gap-1 w-100"
                                    >
                                        <div
                                            class="d-flex flex-column w-50 align-items-start ps-50 gap-75"
                                        >
                                            {{-- Прогресбар % --}}
                                            <div class="d-flex align-items-center w-100">
                                                <div
                                                    class="progress w-100 me-50"
                                                    style="height: 12px"
                                                >
                                                    <div
                                                        class="progress-bar bg-success"
                                                        role="progressbar"
                                                        aria-valuenow="{{ $percent }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        style="width: {{ $percent }}%"
                                                    ></div>
                                                </div>
                                                <span class="small w-25">{{ $percent }} %</span>
                                            </div>

                                            {{-- Прогресбар кг --}}
                                            <div class="d-flex align-items-center w-100">
                                                <div
                                                    class="progress w-100 me-50"
                                                    style="height: 12px"
                                                >
                                                    <div
                                                        class="progress-bar bg-warning"
                                                        role="progressbar"
                                                        aria-valuenow="{{ $loadWeight }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="{{ $maxWeight }}"
                                                        style="width: {{ $percent }}%"
                                                    ></div>
                                                </div>
                                                <span class="small w-25">
                                                    {{ $loadWeight }}/{{ $maxWeight }} кг
                                                </span>
                                            </div>
                                        </div>

                                        @if ($loadWeight)
                                            <x-modal.modal-trigger-button
                                                id="view-details"
                                                target="view-details-trigger"
                                                class="btn btn-flat-secondary w-auto d-flex gap-1 align-items-center flex-row-reverse"
                                                :text="__('localization.container-register.view.view_content')"
                                                :icon="'edit'"
                                            />
                                        @endif
                                    </div>
                                </x-card.card-data-row>
                            @endif
                        </x-card.card-data-wrapper>
                    </x-ui.col-row-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    <!-- Delete warehouse modal -->
    <x-modal.delete-modal
        modalId="delete-modal"
        :action="route('container-register.destroy', ['container_register' => $containerRegister->id])"
        title="localization.container-register.view.modal_delete.title"
        description="localization.container-register.view.modal_delete.confirmation"
        cancelText="localization.container-register.view.modal_delete.cancel"
        confirmText="localization.container-register.view.modal_delete.submit"
        :use-button-instead-of-form="true"
    />

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

    @if ($loadWeight)
        <x-modal.base
            id="view-details-trigger"
            size="modal-lg"
            style="max-width: 1800px !important"
        >
            <x-slot name="header">
                <x-ui.section-card-title level="4" class="modal-title">
                    {{ __('localization.container-register.view.table.title') }}
                </x-ui.section-card-title>

                <button
                    type="button"
                    class="btn btn-link position-absolute end-0 me-1"
                    data-bs-target="#view-details-trigger"
                    data-bs-toggle="modal"
                    data-dismiss="modal"
                >
                    <i data-feather="x"></i>
                </button>
            </x-slot>

            <x-slot name="body">
                <div class="card-grid" style="position: relative">
                    @include('layouts.table-setting')
                    <div class="table-block" id="view-details-table"></div>
                </div>
            </x-slot>
        </x-modal.base>
    @endif
@endsection

@section('page-script')
    <script>
        let containerRegisterId = {!! json_encode($containerRegister->id) !!};
    </script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#view-details-table'), '', 200, 250);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#view-details-table'));
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/container-register/container-register.js') }}"
    ></script>
@endsection
