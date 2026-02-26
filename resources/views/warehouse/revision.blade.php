@extends('layouts.admin')
@section('title', __('localization.warehouse.view.title'))

@section('page-style')
    
@endsection

@section('content')
    <x-layout.container id="warehouse-container" data-id="{{ $warehouse->id }}">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/warehouses',
                            'name' => __('localization.warehouse.view.breadcrumb.warehouses'),
                        ],
                        [
                            'name' => __('localization.warehouse.view.breadcrumb.current'),
                            'name2' => $warehouse->name ?? __('localization.warehouse.view.no_name'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-ui.icon-link-button
                    href="{{ route('warehouses.edit', ['warehouse' => $warehouse->id]) }}"
                    :title="__('localization.warehouse.view.edit_icon_tooltip')"
                    icon="edit"
                />

                <x-ui.icon-dropdown
                    id="header-dropdown"
                    menuClass="d-flex flex-column justify-content-center px-1 gap-25"
                >
                    <x-modal.modal-trigger-button
                        id="cancel_button"
                        target="delete-modal"
                        class="btn btn-flat-danger {{$warehouse->hasLeftovers() ? 'disabled' : ''}}"
                        :text="__('localization.warehouse.view.modal_delete_confirm')"
                    />
                </x-ui.icon-dropdown>
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested>
                <x-slot:header>
                    <div class="d-flex flex-column px-1">
                        <x-ui.section-card-title level="4">
                            {{ $warehouse->name }}
                        </x-ui.section-card-title>

                        <a
                            href="{{ route('locations.show', ['location' => $warehouse->location->id]) }}"
                            style="color: #4b465c"
                        >
                            <p style="text-decoration: underline">
                                {{ $warehouse->location->name ?? $warehouse->location->surname . ' ' . $warehouse->location->first_name . ' ' . $warehouse->location->patronymic }}
                            </p>
                        </a>
                    </div>
                </x-slot>
                <x-slot:body>
                    <x-ui.section-divider class="mt-1 mb-2" />

                    <x-ui.col-row-wrapper>
                        <x-ui.section-card-title level="5">
                            {{ __('localization.warehouse.view.main_data') }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <x-card.card-data-row
                                :label="__('localization.warehouse.view.type')"
                                :value="$warehouse->type->name"
                            />

                            @if ($warehouse->warehouseERP)
                                <x-card.card-data-row
                                    :label="__('localization.warehouse.view.erp_warehouse')"
                                    :value="count($warehouse->warehouseERP)
                                        ? collect($warehouse->warehouseERP)->pluck('name')->join(', ')
                                    : __('localization.warehouse.view.no_erp_warehouse')"
                                />
                            @endif

                            <x-card.card-data-row
                                :label="__('localization.warehouse.view.components_warehouse')"
                                :copyButton="false"
                            >
                                <a
                                    class="btn-outline-secondary btn py-50 px-1"
                                    href="{{ route('warehouses.zones.index', ['warehouse' => $warehouse->id]) }}"
                                >
                                    {{ __('localization.warehouse.view.add') }}
                                    <i data-feather="plus"></i>
                                </a>
                            </x-card.card-data-row>

                            <x-card.card-data-row
                                :label="__('localization.warehouse.view.map_warehouse')"
                                :copyButton="false"
                            >
                                <a
                                    class="btn-outline-secondary btn py-50 px-1"
                                    href="{{ route('warehouse.map') }}"
                                >
                                    {{ __('localization.warehouse.view.add') }}
                                    <i data-feather="plus"></i>
                                </a>
                            </x-card.card-data-row>
                        </x-card.card-data-wrapper>

                        @if (count($warehouse->schedule))
                            <x-ui.section-card-title level="5">
                                {{ __('localization.warehouse.view.working_hours_title') }}
                            </x-ui.section-card-title>

                            @if (count($warehouse->schedule))
                                <div class="calendar-container">
                                    <div class="calendar-header my-1">
                                        <div
                                            class="calendar-navigation d-flex align-items-center justify-content-between justify-content-md-start row mx-0"
                                        >
                                            <div class="col-auto d-flex justify-content-center p-0">
                                                <button
                                                    data-bs-toggle="tooltip"
                                                    title="{{ __('localization.warehouse.view.calendar_header_prev_button') }}"
                                                    class="btn p-0 button-calendar rounded-5"
                                                    id="prevWeekButton"
                                                >
                                                    <i data-feather="chevron-left"></i>
                                                </button>
                                            </div>

                                            <h5
                                                class="col-auto col-sm-auto col-md-5 col-lg-3 p-0 mb-0 text-center text-secondary"
                                                id="currentMonthYear"
                                            ></h5>

                                            <div class="col-auto d-flex justify-content-center p-0">
                                                <button
                                                    title="{{ __('localization.warehouse.view.calendar_header_next_button') }}"
                                                    data-bs-toggle="tooltip"
                                                    class="btn p-0 button-calendar rounded-5"
                                                    id="nextWeekButton"
                                                >
                                                    <i data-feather="chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table
                                            class="table css-calendar-table table-hover table-striped table-borderless"
                                        >
                                            <thead>
                                                <tr
                                                    class="border border-top-0 border-end-0 border-start-0"
                                                >
                                                    <th
                                                        class="text-nowrap text-secondary fs-5 fw-bold"
                                                    >
                                                        {{ __('localization.warehouse.view.calendar_table_day_column') }}
                                                    </th>
                                                    <th
                                                        class="text-nowrap text-secondary fs-5 fw-bold"
                                                    >
                                                        {{ __('localization.warehouse.view.calendar_table_workday_column') }}
                                                    </th>
                                                    <th
                                                        class="text-nowrap text-secondary fs-5 fw-bold"
                                                    >
                                                        {{ __('localization.warehouse.view.calendar_table_lunch_column') }}
                                                    </th>
                                                    <th class="text-nowrap"></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="fw-bold mb-0">
                                    {{ __('localization.warehouse.view.calendar_no_schedule') }}
                                </div>
                            @endif
                        @endif
                    </x-ui.col-row-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    <!-- Delete warehouse modal -->
    <x-modal.delete-modal
        modalId="delete-modal"
        :action="route('warehouses.destroy', ['warehouse' => $warehouse->id])"
        title="localization.warehouse.view.modal_delete.title"
        description="localization.warehouse.view.modal_delete.confirmation"
        cancelText="localization.warehouse.view.modal_delete.cancel"
        confirmText="localization.warehouse.view.modal_delete.submit"
        :use-button-instead-of-form="true"
    />
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script>
        let conditions = {!! json_encode($warehouse->conditions) !!};

        let exceptions = {!! json_encode($exceptions) !!};

        let exceptionsArray = [];

        for (let i = 0; i < exceptions.length; i++) {
            exceptionsArray[exceptions[i].id] = exceptions[i].name;
        }

        let schedule = [];
        //console.log(conditions, exceptions, exceptionsArray)

        @foreach($warehouse->schedule as $row)

        // Додати конвертований рядок JSON у масив JavaScript
        schedule.push({!! json_encode($row) !!});

        // Використовуйте значення змінної PHP в JavaScript
        //console.log(phpVariable);

        @endforeach

        // console.log(schedule)
    </script>

    <script type="module" src="{{ asset('assets/js/utils/calendar.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/warehouse/crud.js') }}"></script>
@endsection
