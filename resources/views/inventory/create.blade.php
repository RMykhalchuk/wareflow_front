@extends('layouts.admin')
@section('title', __('localization.inventory.create.title'))

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}"
    />
@endsection

@section('content')
    @php
        $inventoryType = request()->get('type', 'partly'); // за замовчуванням full
    @endphp

    <x-layout.container id="inventory-container" data-type="{{ $inventoryType }}">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/inventory',
                            'name' => __('localization.inventory.create.title'),
                        ],
                        [
                            'name' => __('localization.inventory.create.create_inventory'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel_button_modal"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            {{-- Full Inventory --}}
            <div
                id="full_inventory"
                style="display: {{ $inventoryType === 'full' ? 'block' : 'none' }}"
            >
                <div class="d-flex gap-1 align-items-center">
                    <x-page-title
                        :title="__('localization.inventory.create.title').' № '.$lastLocalId"
                    />
                    <span class="badge bg-dark fw-bold mt-2" style="color: #fff !important">
                        {{ __('localization.inventory.create.full') }}
                    </span>
                </div>
                <x-card.nested id="block_1_full">
                    <x-slot:header>
                        <x-section-title>
                            {{ __('localization.inventory.create.general_data') }}
                        </x-section-title>
                    </x-slot>

                    <x-slot:body>
                        <x-form.switch
                            id="show_leftovers_full"
                            name="show_leftovers_full"
                            label="localization.inventory.create.with_hint"
                            class="w-full mb-1 d-none"
                            :checked="true"
                        />

                        <x-form.switch
                            id="restrict_goods_movement_full"
                            name="restrict_goods_movement_full"
                            disabled
                            label="localization.inventory.create.block_goods_movement"
                            class="w-full mb-1"
                            :checked="true"
                        />

                        <x-form.select
                            id="performer_type_full"
                            name="performer_type_full"
                            label="localization.inventory.create.performer"
                            placeholder="localization.inventory.create.choose_inventory_performers"
                            data-dictionary="users"
                            multiple
                        />

                        <div class="col-6 d-flex flex-column gap-25">
                            <div>{{ __('localization.inventory.create.priority') }}</div>
                            <div id="priority_full" class="d-flex flex-wrap gap-25">
                                @foreach (range(0, 10) as $i)
                                    <button
                                        type="button"
                                        class="priority-btn {{ $i === 0 ? 'active' : '' }}"
                                        data-value="{{ $i }}"
                                    >
                                        {{ $i }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <x-form.date-input
                            id="start_date_full"
                            name="start_date_full"
                            label="localization.inventory.create.start_date"
                            placeholder="localization.inventory.create.start_date"
                            :required
                            :dateTime="true"
                        />

                        <x-form.date-input
                            id="end_date_full"
                            name="end_date_full"
                            label="localization.inventory.create.end_date"
                            placeholder="localization.inventory.create.end_date"
                            :required
                            :dateTime="true"
                        />

                        <x-form.textarea
                            id="comment_full"
                            name="comment_full"
                            label=""
                            placeholder="localization.inventory.create.comment"
                        />

                        <div class="mt-1" id="main-data-message_full"></div>
                    </x-slot>
                </x-card.nested>

                <x-card.nested id="block_2_full">
                    <x-slot:header>
                        <x-section-title>
                            {{ __('localization.inventory.create.location') }}
                        </x-section-title>
                    </x-slot>

                    <x-slot:body>
                        <x-form.select
                            id="warehouse_full"
                            name="warehouse_full"
                            label="localization.inventory.create.warehouse"
                            placeholder="localization.inventory.create.select_warehouse"
                            data-dictionary="warehouses"
                        />
                        <x-form.select
                            id="warehouse_erp_full"
                            name="warehouse_erp_full"
                            label="localization.inventory.create.warehouse_erp"
                            placeholder="localization.inventory.create.select_warehouse_erp"
                            data-dictionary="warehouses_erp"
                        />
                        <x-form.input-group-wrapper wrapperClass="mb-0 p-0">
                            <p class="mb-50">
                                {{ __('localization.inventory.create.process_cell') }}
                            </p>

                            <div class="d-flex gap-1">
                                <div class="m-0">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="process_cell_full"
                                        id="process_cell_1_full"
                                        autocomplete="off"
                                        checked
                                    />
                                    <label
                                        class="btn btn-outline-primary d-flex align-items-center gap-1"
                                        for="process_cell_1_full"
                                    >
                                        <input
                                            type="radio"
                                            class="form-check-input pointer-events-none"
                                            checked
                                        />
                                        <span>
                                            {{ __('localization.inventory.create.without_restrictions') }}
                                        </span>
                                    </label>
                                </div>

                                <div class="m-0">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="process_cell_full"
                                        id="process_cell_2_full"
                                        autocomplete="off"
                                    />
                                    <label
                                        class="btn btn-outline-primary d-flex align-items-center gap-1"
                                        for="process_cell_2_full"
                                    >
                                        <input
                                            type="radio"
                                            class="form-check-input pointer-events-none"
                                        />
                                        <span>
                                            {{ __('localization.inventory.create.empty_only') }}
                                        </span>
                                    </label>
                                </div>

                                <div class="m-0">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="process_cell_full"
                                        id="process_cell_3_full"
                                        autocomplete="off"
                                    />
                                    <label
                                        class="btn btn-outline-primary d-flex align-items-center gap-1"
                                        for="process_cell_3_full"
                                    >
                                        <input
                                            type="radio"
                                            class="form-check-input pointer-events-none"
                                        />
                                        <span>
                                            {{ __('localization.inventory.create.filled_only') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </x-form.input-group-wrapper>

                        <div class="mt-1" id="location-data-message_full"></div>
                    </x-slot>
                </x-card.nested>
            </div>

            {{-- Partly Inventory --}}
            <div
                id="part_inventory"
                style="display: {{ $inventoryType === 'partly' ? 'block' : 'none' }}"
            >
                <div class="d-flex gap-1 align-items-center">
                    <x-page-title
                        :title="__('localization.inventory.create.title').' № '.$lastLocalId"
                    />

                    <span class="badge bg-dark fw-bold mt-2" style="color: #fff !important">
                        {{ __('localization.inventory.create.partial') }}
                    </span>
                </div>

                <x-card.nested id="block_1_part">
                    <x-slot:header>
                        <x-section-title>
                            {{ __('localization.inventory.create.main_information') }}
                        </x-section-title>
                    </x-slot>

                    <x-slot:body>
                        <x-form.switch
                            id="show_leftovers_part"
                            name="show_leftovers_part"
                            :label="__('localization.inventory.create.show_leftovers_on_terminal')"
                            class="w-full mb-1 d-none"
                            :checked="true"
                        />

                        <x-form.switch
                            id="restrict_goods_movement_part"
                            name="restrict_goods_movement_part"
                            disabled
                            :label="__('localization.inventory.create.restrict_goods_movement')"
                            class="w-full mb-1"
                            :checked="true"
                        />

                        <x-form.select
                            id="performer_type_part"
                            name="performer_type_part"
                            :label="__('localization.inventory.create.performer')"
                            :placeholder="__('localization.inventory.create.choose_inventory_performers')"
                            data-dictionary="users"
                            multiple
                        />

                        <div class="col-6 d-flex flex-column gap-25">
                            <div>{{ __('localization.inventory.create.priority') }}</div>
                            <div id="priority_part" class="d-flex flex-wrap gap-25">
                                @foreach (range(0, 10) as $i)
                                    <button
                                        type="button"
                                        class="priority-btn {{ $i === 0 ? 'active' : '' }}"
                                        data-value="{{ $i }}"
                                    >
                                        {{ $i }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <x-form.date-input
                            id="start_date_part"
                            name="start_date_part"
                            :label="__('localization.inventory.create.start_date')"
                            :placeholder="__('localization.inventory.create.start_date')"
                            :required
                            :dateTime="true"
                        />

                        <x-form.date-input
                            id="end_date_part"
                            name="end_date_part"
                            :label="__('localization.inventory.create.end_date')"
                            :placeholder="__('localization.inventory.create.end_date')"
                            :required
                            :dateTime="true"
                        />

                        <x-form.textarea
                            id="comment_part"
                            name="comment_part"
                            label=""
                            placeholder="localization.inventory.create.comment"
                        />

                        <div class="mt-1" id="main-data-message_part"></div>
                    </x-slot>
                </x-card.nested>

                <x-card.nested id="block_2_part">
                    <x-slot:header>
                        <x-section-title>
                            {{ __('localization.inventory.create.location') }}
                        </x-section-title>
                    </x-slot>

                    <x-slot:body>
                        <x-form.select
                            id="warehouse_part"
                            name="warehouse_part"
                            :label="__('localization.inventory.create.warehouse')"
                            :placeholder="__('localization.inventory.create.select_warehouse')"
                            data-dictionary="warehouses"
                        />
                        <x-form.select
                            id="warehouse_erp_part"
                            name="warehouse_erp_part"
                            :label="__('localization.inventory.create.warehouse_erp')"
                            :placeholder="__('localization.inventory.create.select_warehouse_erp')"
                            data-dictionary="warehouses_erp"
                        />

                        <x-form.select
                            id="zone_part"
                            name="zone_part"
                            :label="__('localization.inventory.create.zone')"
                            :placeholder="__('localization.inventory.create.select_zone')"
                            data-dependent="warehouse_part"
                            data-dependent-param="warehouse_id"
                            data-dictionary-base="zones"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                        />

                        <x-form.select
                            id="sector_part"
                            name="sector_part"
                            :label="__('localization.inventory.create.sector')"
                            :placeholder="__('localization.inventory.create.select_sector')"
                            data-dependent="zone_part"
                            data-dependent-param="zone_id"
                            data-dictionary-base="sectors"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                        />

                        <x-form.select
                            id="row_part"
                            name="row_part"
                            :label="__('localization.inventory.create.row')"
                            :placeholder="__('localization.inventory.create.select_row')"
                            data-dependent="sector_part"
                            data-dependent-param="sector_id"
                            data-dictionary-base="rows"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                        />
                        <x-form.select
                            id="cell_part"
                            name="cell_part"
                            :label="__('localization.inventory.create.cell')"
                            :placeholder="__('localization.inventory.create.select_cell')"
                            data-dependent="row_part"
                            data-dependent-param="row_id"
                            data-dictionary-base="cells"
                            data-dictionary-textfield="code"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                        />

                        <x-form.input-group-wrapper wrapperClass="mb-0 p-0">
                            <p class="mb-50">
                                {{ __('localization.inventory.create.process_cell') }}
                            </p>

                            <div class="d-flex gap-1">
                                <div class="m-0">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="process_cell_partly"
                                        id="process_cell_1_partly"
                                        autocomplete="off"
                                        checked
                                    />
                                    <label
                                        class="btn btn-outline-primary d-flex align-items-center gap-1"
                                        for="process_cell_1_partly"
                                    >
                                        <input
                                            type="radio"
                                            class="form-check-input pointer-events-none"
                                            checked
                                        />
                                        <span>
                                            {{ __('localization.inventory.create.without_restrictions') }}
                                        </span>
                                    </label>
                                </div>

                                <div class="m-0">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="process_cell_partly"
                                        id="process_cell_2_partly"
                                        autocomplete="off"
                                    />
                                    <label
                                        class="btn btn-outline-primary d-flex align-items-center gap-1"
                                        for="process_cell_2_partly"
                                    >
                                        <input
                                            type="radio"
                                            class="form-check-input pointer-events-none"
                                        />
                                        <span>
                                            {{ __('localization.inventory.create.empty_only') }}
                                        </span>
                                    </label>
                                </div>

                                <div class="m-0">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="process_cell_partly"
                                        id="process_cell_3_partly"
                                        autocomplete="off"
                                    />
                                    <label
                                        class="btn btn-outline-primary d-flex align-items-center gap-1"
                                        for="process_cell_3_partly"
                                    >
                                        <input
                                            type="radio"
                                            class="form-check-input pointer-events-none"
                                        />
                                        <span>
                                            {{ __('localization.inventory.create.filled_only') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </x-form.input-group-wrapper>

                        <div class="mt-1" id="location-data-message_part"></div>
                    </x-slot>
                </x-card.nested>

                <x-card.nested id="block_3_part" style="display: none">
                    <x-slot:header>
                        <x-section-title>
                            {{ __('localization.inventory.create.nomenclature') }}
                        </x-section-title>
                    </x-slot>

                    <x-slot:body>
                        <x-form.select
                            id="category_subcategory_part"
                            name="category_subcategory_part"
                            {{-- multiple --}}
                            :label="__('localization.inventory.create.category_subcategory')"
                            :placeholder="__('localization.inventory.create.select_category_subcategory')"
                            data-dictionary="category"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                            {{-- data-edit="true" --}}
                        />

                        <x-form.select
                            id="manufacturer_part"
                            name="manufacturer_part"
                            {{-- multiple --}}
                            :label="__('localization.inventory.create.manufacturer')"
                            :placeholder="__('localization.inventory.create.select_manufacturer')"
                            data-dictionary="company"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                            {{-- data-edit="true" --}}
                        />

                        <x-form.select
                            id="brand_part"
                            name="brand_part"
                            {{-- multiple --}}
                            :label="__('localization.inventory.create.brand')"
                            :placeholder="__('localization.inventory.create.select_brand')"
                            data-dictionary="company"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                            {{-- data-edit="true" --}}
                        />

                        <x-form.select
                            id="supplier_part"
                            name="supplier_part"
                            {{-- multiple --}}
                            :label="__('localization.inventory.create.supplier')"
                            :placeholder="__('localization.inventory.create.select_supplier')"
                            data-dictionary="company"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                            {{-- data-edit="true" --}}
                        />

                        <x-form.select
                            id="nomenclature_part"
                            name="nomenclature_part"
                            multiple
                            :label="__('localization.inventory.create.nomenclature')"
                            :placeholder="__('localization.inventory.create.select_nomenclature')"
                            data-dictionary="goods"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                            {{-- data-edit="true" --}}
                        />

                        <div class="mt-1" id="nomenclature-data-message_part"></div>
                    </x-slot>
                </x-card.nested>
            </div>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="create"
                    class="btn btn-primary mb-2"
                    :text="__('localization.warehouse.create.save_button')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancel_button_modal"
        route="/inventory"
        title="{{ __('localization.inventory.cancel.create.modal.title') }}"
        content="{!! __('localization.inventory.cancel.create.modal.content') !!}"
        cancel-text="{{ __('localization.inventory.cancel.cancel_button') }}"
        confirm-text="{{ __('localization.inventory.cancel.confirm_button') }}"
    />
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script>
        document.querySelectorAll('.btn-check').forEach((btnCheck) => {
            btnCheck.addEventListener('change', () => {
                const allGroups = document.querySelectorAll(`input[name="${btnCheck.name}"]`);

                allGroups.forEach((radio) => {
                    const label = document.querySelector(`label[for="${radio.id}"]`);
                    const innerInput = label?.querySelector('.form-check-input');
                    if (innerInput) {
                        innerInput.checked = radio.checked;
                    }
                });
            });
        });
    </script>

    <script type="module" src="{{ asset('assets/js/entity/inventory/inventory.js') }}"></script>

    <script
        type="module"
        src="{{ asset('assets/js/utils/dictionary/selectDictionaryRelated.js') }}"
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            flatpickr('.flatpickr-date-time', {
                // клас для datetime полів
                enableTime: true, // дозволяє вибір часу
                altInput: true, // гарний формат для користувача
                altFormat: 'd.m.Y H:i', // як показується
                dateFormat: 'Y-m-d H:i', // як зберігається
                locale: lang,
            });
        });
    </script>

    <script>
        document.querySelectorAll('.priority-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                document
                    .querySelectorAll('.priority-btn')
                    .forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                console.log('Вибраний пріоритет:', btn.dataset.value);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[name="process_cell_partly"]');
            const block3 = document.getElementById('block_3_part');

            const toggleBlock3 = () => {
                const checkedRadio = document.querySelector(
                    'input[name="process_cell_partly"]:checked'
                );
                // Показати тільки якщо вибрано process_cell_3_partly
                block3.style.display =
                    checkedRadio && checkedRadio.id === 'process_cell_3_partly' ? 'block' : 'none';
                //     todo може тут пофіксати той прикол із селектами
            };

            // Додаємо слухачів на зміну радіо
            radios.forEach((radio) => radio.addEventListener('change', toggleBlock3));

            // Викликаємо одразу для відображення/приховання при завантаженні сторінки
            toggleBlock3();
        });
    </script>

    <script type="module">
        import { initSelectAllLogic } from '{{ asset('assets/js/utils/dictionary/selectAllLogic.js') }}';

        document.addEventListener('DOMContentLoaded', () => {
            initSelectAllLogic(['#nomenclature_part']);
        });
    </script>
@endsection
