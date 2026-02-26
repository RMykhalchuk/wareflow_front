@extends('layouts.admin')
@section('title', 'Перегляд комплекту')
@section("page-style")
    
@endsection

@section("content")
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/sku',
                            'name' => __('localization.sku_view_breadcrumb_products'),
                        ],
                        [
                            'name' => __('localization.kits.breadcrumb.view') . ': ' . $sku->name,
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                {{-- <x-ui.icon-button --}}
                {{-- id="jsPrintBtn" --}}
                {{-- :title="__('localization.sku_view_printer_icon_tooltip')" --}}
                {{-- icon="printer" --}}
                {{-- /> --}}

                {{-- <x-ui.icon-button --}}
                {{-- id="jsCopyBtn" --}}
                {{-- :title="__('localization.sku_view_printer_icon_tooltip')" --}}
                {{-- icon="copy" --}}
                {{-- /> --}}

                <x-ui.icon-dropdown
                    icon="chevron-down"
                    id="header-dropdown"
                    :text="__('localization.sku_view_more_actions_logs')"
                    menuClass="d-flex flex-column justify-content-center px-1 gap-25"
                >
                    <x-ui.dropdown-item
                        href="#"
                        :text="__('localization.kits.logs.use_in_tasks')"
                    />

                    {{-- <x-ui.dropdown-item --}}
                    {{-- href="#" --}}
                    {{-- icon="info" --}}
                    {{-- :text="__('localization.sku_view_more_actions_use_product')" --}}
                    {{-- /> --}}
                    {{-- <x-ui.dropdown-item --}}
                    {{-- href="#" --}}
                    {{-- icon="info" --}}
                    {{-- :text="__('localization.sku_view_more_actions_move_pc_pallet')" --}}
                    {{-- /> --}}
                    {{-- <x-ui.dropdown-item --}}
                    {{-- href="#" --}}
                    {{-- icon="info" --}}
                    {{-- :text="__('localization.sku_view_more_actions_product_movement')" --}}
                    {{-- /> --}}
                </x-ui.icon-dropdown>

                <div class="vr mx-0 my-100 bg-secondary-subtle"></div>

                <x-ui.icon-link-button
                    href="{{ route('sku.kits.edit', ['sku' => $sku->id]) }}"
                    :title="__('localization.sku_view_edit_icon_tooltip')"
                    icon="edit"
                />

                <x-ui.icon-dropdown
                    id="header-dropdown"
                    menuClass="d-flex flex-column justify-content-center px-1 gap-25"
                >
                    <x-modal.modal-trigger-button
                        id="cancel_button"
                        target="deleteGoodsModal"
                        class="btn btn-flat-danger"
                        :text="__('localization.sku_view_more_actions_deactivate')"
                    />
                </x-ui.icon-dropdown>
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested id="jsCopyEventScope">
                <x-slot:header>
                    <div class="d-flex flex-grow-1 align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <img
                                src="{{
                                    $sku->img_type
                                        ? '/files/uploads/goods/' . $sku->id . '.' . $sku->img_type
                                        : asset('assets/icons/entity/goods/avatar-default.svg')
                                }}"
                                id="account-upload-img"
                                class="uploadedAvatar rounded"
                                alt="profile image"
                                height="80"
                                width="80"
                            />
                            <div class="d-flex justify-content-center flex-column gap-50">
                                <x-ui.section-card-title level="4">
                                    {{ $sku->name }}
                                </x-ui.section-card-title>
                                <div class="d-flex">
                                    @foreach ($sku->barcodes as $barcodeObj)
                                        <p class="mb-0">
                                            {{ $barcodeObj["barcode"] }}@if (! $loop->last),
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-25">
                            <div class="fw-bolder">
                                {{ __("localization.sku.view.leftovers.title") }}
                            </div>
                            <div class="d-flex justify-content-between gap-1">
                                <div>WMS:</div>
                                <div class="fw-bolder">
                                    {{ $sku->leftovers_wms_total !== null ? $sku->leftovers_wms_total . " " . __("localization.measurement_unit_" . $sku->measurement_unit->key) : "-" }}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-1">
                                <div>ERP:</div>
                                <div class="fw-bolder">
                                    {{ $sku->leftovers_erp_total !== null ? $sku->leftovers_erp_total . " " . __("localization.measurement_unit_" . $sku->measurement_unit->key) : "-" }}
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-slot:body>
                    <x-ui.section-divider class="mt-50" />
                    <x-ui.col-row-wrapper>
                        <x-ui.section-card-title level="5">
                            {{ __("localization.sku_view_basic_data") }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <x-card.card-data-row :label="__('localization.sku_view_category')">
                                <p class="m-0 fw-medium-c">
                                    @switch($sku->category->key ?? null)
                                        @case("product")
                                            {{ __("localization.sku_view_category_product") }}

                                            @break
                                        @case("product_t")
                                            {{ __("localization.sku_view_category_product_t") }}

                                            @break
                                        @case("vydub_prom")
                                            {{ __("localization.sku_view_category_vydub_prom") }}

                                            @break
                                        @case("textile")
                                            {{ __("localization.sku_view_category_textile") }}

                                            @break
                                        @case("bud_material")
                                            {{ __("localization.sku_view_category_bud_material") }}

                                            @break
                                        @case("poligraph_prod")
                                            {{ __("localization.sku_view_category_poligraph_prod") }}

                                            @break
                                        @case("sport_prylad")
                                            {{ __("localization.sku_view_category_sport_prylad") }}

                                            @break
                                        @case("naft_prod")
                                            {{ __("localization.sku_view_category_naft_prod") }}

                                            @break
                                        @case("material_synt")
                                            {{ __("localization.sku_view_category_material_synt") }}

                                            @break
                                        @case("vyroby_zi_skla")
                                            {{ __("localization.sku_view_category_vyroby_zi_skla") }}

                                            @break
                                        @case("elektronika")
                                            {{ __("localization.sku_view_category_elektronika") }}

                                            @break
                                        @case("mebli")
                                            {{ __("localization.sku_view_category_mebli") }}

                                            @break
                                        @case("pryrodna_s")
                                            {{ __("localization.sku_view_category_pryrodna_s") }}

                                            @break
                                        @case("tsinni_mat")
                                            {{ __("localization.sku_view_category_tsinni_mat") }}

                                            @break
                                        @case("other")
                                            {{ __("localization.sku_view_category_other") }}

                                            @break
                                        @case("raw")
                                            {{ __("localization.sku_view_category_raw") }}

                                            @break
                                        @case("pobut")
                                            {{ __("localization.sku_view_category_pobut") }}

                                            @break
                                        @default
                                            {{ $sku?->category?->name }}
                                    @endswitch
                                </p>
                            </x-card.card-data-row>
                        </x-card.card-data-wrapper>

                        <x-ui.section-card-title level="5">
                            {{ __("localization.kits.content.title") }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <div class="table-responsive" id="kits-table-container"></div>
                        </x-card.card-data-wrapper>

                        <x-ui.section-card-title level="5">
                            {{ __("localization.sku_view_parameters") }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            @if (! empty($sku->measurement_unit?->key))
                                <x-card.card-data-row
                                    :label="__('localization.sku_view_measurement_unit')"
                                    :value="__('localization.measurement_unit_' . $sku->measurement_unit->key)"
                                />
                            @endif

                            {{-- todo             костиль прибрати --}}
                            <x-form.select
                                id="measurement_unit_id"
                                name="measurement_unit_id"
                                label="localization.sku_edit_measurement_unit_label"
                                placeholder="localization.sku_edit_measurement_unit_placeholder"
                                data-dictionary="measurement_unit"
                                data-id="{{ $sku->measurement_unit_id }}"
                                data-unit-text="{{ $sku->measurement_unit?->key ? __('localization.measurement_unit_' . $sku->measurement_unit->key) : '' }}"
                                class="d-none"
                            />

                            <x-card.card-data-row
                                :label="__('localization.sku_view_dimensions')"
                                :value="sprintf('%sx%sx%s %s', $sku->height, $sku->width, $sku->length, __('localization.sku_view_dimensions_unit'))"
                            />

                            <x-card.card-data-row
                                :label="__('localization.sku_view_net_weight')"
                                :value="sprintf('%.2f %s', $sku->weight_netto, __('localization.sku_view_net_weight_unit'))"
                            />

                            <x-card.card-data-row
                                :label="__('localization.sku_view_gross_weight')"
                                :value="sprintf('%.2f %s', $sku->weight_brutto, __('localization.sku_view_gross_weight_unit'))"
                            />
                        </x-card.card-data-wrapper>

                        <x-ui.section-card-title level="5">
                            {{ __("localization.sku_view_packing") }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <div class="table-responsive" id="table-container"></div>
                        </x-card.card-data-wrapper>
                    </x-ui.col-row-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    <!-- Delete goods modal -->
    <x-modal.delete-modal
        modalId="deleteGoodsModal"
        :action="route('sku.destroy', ['sku' => $sku->id])"
        title="localization.sku_view_delete_product_modal_title"
        description="localization.sku_view_delete_product_confirmation"
        cancelText="localization.sku_view_delete_product_cancel"
        confirmText="localization.sku_view_delete_product_confirm"
    />
@endsection

@section("page-script")
    <script>
        const sku_id = {!! $sku->id !!};
    </script>

    <script>
        let selectedItemData = null;
        let selectedItemDataPackage = null;
        let editIndex = null;
    </script>

    <script type="module">
        import { renderKitsTable } from '{{ asset('assets/js/entity/sku/kits-table.js') }}';

        @if (!empty($sku->goodsKitItems))
        // Отримуємо сирі дані з бекенду
        const rawData = @json($sku->goodsKitItems);

        // Перетворюємо у потрібний формат
        window.tableDataKits = rawData.map((item, index) => ({
            goods_id: item.goods?.id ?? null,
            local_id: item.goods?.local_id ?? null,
            name: item.goods?.name ?? '-',
            package_id: item.packages?.id ?? null,
            package_name: item.packages?.name ?? '-',
            quantity: item.quantity ?? 0,
            position: index + 1,
            isEdit: true, // <-- додаємо прапорець
        }));
        renderKitsTable(window.tableDataKits, false);
        @else
            window.tableDataKits = [];
        renderKitsTable(window.tableDataKits, false);
        @endif
    </script>

    <script type="module">
        import { renderTable } from '{{ asset("assets/js/entity/sku/packaging-tree-table.js") }}';

        let tableData = @json($sku->packages);

        // 1️⃣ Спочатку створюємо idMap для parent_id
        const idMap = {};
        tableData.forEach((item, index) => {
            idMap[item.id] = (index + 1).toString();
        });

        // 2️⃣ Формуємо початкові дані без назв типів
        tableData = tableData.map((item, index) => ({
            id: (index + 1).toString(),
            parent_id: item.parent_id ? idMap[item.parent_id] || null : null,
            type_id: item.type_id,
            type_name: item.type?.name ?? '',
            name: item.name,
            main_units_number: item.main_units_number,
            package_count: item.package_count,
            weight_netto: item.weight_netto,
            weight_brutto: item.weight_brutto,
            height: item.height,
            width: item.width,
            length: item.length,
            barcode: item.barcodeString,
            canEdit: item.canEdit,
            uuid: true,
        }));

        // 3️⃣ Рендеримо таблицю одразу
        renderTable(tableData, false);

        window.tableData = tableData;
    </script>

    {{-- <script type="module"> --}}
    {{-- import { setupPrintButton } from '{{ asset("assets/js/utils/print-btn.js") }}'; --}}

    {{-- setupPrintButton('jsPrintBtn', 'jsCopyEventScope'); --}}
    {{-- </script> --}}

    {{-- <script type="module"> --}}
    {{-- import { setupCopyButton } from '{{ asset("assets/js/utils/copy-btn.js") }}'; --}}

    {{-- setupCopyButton('jsCopyBtn', 'jsCopyEventScope'); --}}
    {{-- </script> --}}

    <script type="module" src="{{ asset('assets/js/entity/sku/kits-table.js') }}"></script>

    <script type="module">
        import { copyGroup } from '{{ asset("assets/js/utils/copy-group.js") }}';

        copyGroup(); // За замовчуванням
    </script>
@endsection
