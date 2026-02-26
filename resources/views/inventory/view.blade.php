@php
    use App\Models\Entities\Inventory\Inventory;
@endphp

@extends('layouts.admin')
@section('title', __('localization.inventory.view.title'))

@section('page-style')
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

@php
    $inventoryType = $inventory->type;
    $isCancelled = $inventory->trashed();
    $statusId = $isCancelled ? Inventory::STATUS_CANCELLED : (int) $inventory->status;
    $statusLabel = $isCancelled ? __('localization.inventory.view.statuses.cancelled') : $inventory->getStatusLabelAttribute();
@endphp

@section('table-js')
    @include('layouts.table-scripts')
    @if ($statusId !== Inventory::STATUS_CREATED)
        <script
            type="module"
            src="{{ asset('assets/js/grid/inventory/inventory-an-animal-table.js') }}"
        ></script>

        <script
            type="module"
            src="{{ asset('assets/js/grid/inventory/inventory-an-animal-erp-table.js') }}"
        ></script>
    @endif

    <script
        type="module"
        src="{{ asset('assets/js/grid/inventory/inventory-venue-table.js') }}"
    ></script>

    <script
        type="module"
        src="{{ asset('assets/js/grid/inventory/inventory-venue-an-animal-table.js') }}"
    ></script>
@endsection

@section('content')
    <x-layout.container
        id="inventory-container"
        data-id-status="{{ $statusId }}"
        data-id="{{ $inventory->id }}"
        fluid
    >
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/inventory',
                            'name' => __('localization.inventory.view.breadcrumb.inventory'),
                        ],
                        [
                            'name' => __('localization.inventory.view.breadcrumb.current'),
                            'name2' => $inventory->local_id
                                ? '#' . $inventory->local_id
                                : __('localization.inventory.view.no_name'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                @if ($statusId === Inventory::STATUS_CREATED && ! $isCancelled)
                    <x-ui.icon-dropdown
                        id="header-dropdown"
                        menuClass="d-flex flex-column justify-content-center px-1 gap-25"
                    >
                        <x-ui.dropdown-item
                            href="{{ route('inventory.edit', ['inventory' => $inventory]) }}"
                            :text="__('localization.inventory.view.header_actions.edit')"
                            class="w-100 btn btn-flat-secondary"
                        />

                        <x-modal.modal-trigger-button
                            id="cancel_button"
                            target="deleteModalCenter"
                            class="btn btn-flat-danger w-100"
                            :text="__('localization.inventory.view.header_actions.deactivate')"
                        />

                        @if ($statusId !== Inventory::STATUS_CREATED)
                            {{-- <x-modal.modal-trigger-button --}}
                            {{-- id="cancel_button" --}}
                            {{-- target="cancel" --}}
                            {{-- class="btn btn-flat-danger" --}}
                            {{-- :text="__('localization.inventory.view.header_actions.cancel')" --}}
                            {{-- /> --}}
                        @endif
                    </x-ui.icon-dropdown>
                @endif
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <div class="d-flex gap-1 align-items-center">
                    <h1 data-inventory-id="{{ $inventory->id }}" class="mb-0">
                        {{ __('localization.inventory.view.inventory') }} №
                        {{ $inventory->local_id }}
                    </h1>
                    <span
                        class="badge {{ $isCancelled ? 'bg-danger' : 'badge-success bg-success' }}"
                    >
                        {{ $statusLabel }}
                    </span>
                </div>

                @if ($statusId === Inventory::STATUS_CREATED && ! $isCancelled)
                    <a href="{{ route('inventory.proceed', $inventory) }}">
                        <button class="btn btn-outline-secondary">
                            {{ __('localization.inventory.view.send_to_work') }}
                        </button>
                    </a>
                @endif

                @if ($statusId === Inventory::STATUS_IN_PROGRESS && ! $isCancelled)
                    <div>
                        {{-- <a href="{{ route('inventory.pause', $inventory) }}"> --}}
                        {{-- <button class="btn btn-outline-secondary"> --}}
                        {{-- <i data-feather="pause"></i> --}}
                        {{-- </button> --}}
                        {{-- </a> --}}

                        <x-modal.modal-trigger-button
                            id="end_button"
                            target="end"
                            class="btn btn-outline-secondary"
                            :text="__('localization.inventory.view.finish_early')"
                        />
                    </div>
                @endif

                @if ($statusId === Inventory::STATUS_FINISHED_BEFORE && ! $isCancelled)
                    <a href="{{ route('inventory.finish', $inventory) }}">
                        <button class="btn btn-success">
                            <i data-feather="check"></i>
                            {{ __('localization.inventory.view.finish_inventory') }}
                        </button>
                    </a>
                @endif
            </div>

            <x-tabs.group class="">
                @slot('items')
                    @if ($inventoryType === 'full')
                        <x-tabs.item
                            id="review_full"
                            :title="__('localization.inventory.view.tabs.review')"
                            :active="true"
                        />
                    @else
                        <x-tabs.item
                            id="review_partly"
                            :title="__('localization.inventory.view.tabs.review')"
                            :active="true"
                        />
                    @endif

                    <x-tabs.item
                        id="venue"
                        :title="__('localization.inventory.view.modals.venue')"
                    />

                    <x-tabs.item
                        id="an_animal"
                        :title="__('localization.inventory.view.tabs.an_animal')"
                    />
                    <x-tabs.item
                        id="an_animal_erp"
                        :title="__('localization.inventory.view.tabs.an_animal_erp')"
                    />
                @endslot

                @slot('content')
                    @if ($inventoryType === 'full')
                        <x-tabs.content id="review_full" :active="true">
                            {{-- Основна інформація --}}
                            <x-ui.info-card
                                :title="__('localization.inventory.view.review.general_data')"
                            >
                                <div class="row mx-0 p-0 gy-1">
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.type') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ __('localization.inventory.view.review.full') }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.start_datetime') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->start_date?->format('Y.m.d H:i') ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex d-none">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.with_a_hint') }}
                                        </span>
                                        <span class="col-6 d-flex align-items-center">
                                            {{-- todo --}}
                                            @if ($inventory->show_leftovers)
                                                <span
                                                    class="rounded-circle bg-success me-25 d-inline-block"
                                                    style="width: 10px; height: 10px"
                                                ></span>
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.show') }}
                                                </span>
                                            @else
                                                <span
                                                    class="rounded-circle bg-danger me-25 d-inline-block"
                                                    style="width: 10px; height: 10px"
                                                ></span>
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.hide') }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.executors') }}
                                        </span>
                                        <span
                                            class="col-6 d-flex align-items-center flex-wrap gap-50"
                                        >
                                            @php
                                                $people = collect($inventory->performers ?? []);

                                                if ($people->isEmpty() && $inventory->performer) {
                                                    $people = collect([$inventory->performer]);
                                                }
                                            @endphp

                                            @forelse ($people as $user)
                                                <span
                                                    class="border rounded p-25 d-inline-flex align-items-center me-50 mb-50"
                                                >
                                                    <img
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->initial() ?? '') }}&size=24"
                                                        class="rounded-circle me-25"
                                                        width="24"
                                                        height="24"
                                                        alt="{{ $user->initial() }}"
                                                    />
                                                    {{ $user->initial() }}
                                                </span>
                                            @empty
                                                <span class="text-muted">-</span>
                                            @endforelse
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.end_datetime') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->end_date?->format('Y.m.d H:i') ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex d-none">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.movement') }}
                                        </span>
                                        <span class="col-6 d-flex align-items-center">
                                            @if ($inventory->restrict_goods_movement)
                                                <span
                                                    class="rounded-circle bg-success me-25 d-inline-block"
                                                    style="width: 10px; height: 10px"
                                                ></span>
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.stop') }}
                                                </span>
                                            @else
                                                <span
                                                    class="rounded-circle bg-danger me-25 d-inline-block"
                                                    style="width: 10px; height: 10px"
                                                ></span>
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.no_stop') }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="col-6 d-flex align-items-center">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.priority') }}
                                        </span>
                                        <div
                                            id="priority_full_dropdown"
                                            class="priority-container col-6 flex-wrap gap-25"
                                        >
                                            @foreach (range(0, 10) as $i)
                                                <button
                                                    type="button"
                                                    disabled
                                                    class="priority-btn {{ $i === $inventory->priority ? 'active show' : '' }}"
                                                    data-value="{{ $i }}"
                                                >
                                                    {{ $i }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.comment') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->comment ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </x-ui.info-card>

                            {{-- Місце проведення --}}
                            <x-ui.info-card :title="__('localization.inventory.view.place.title')">
                                <div class="row mx-0 p-0 gy-1">
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.warehouse') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->warehouse?->name }}
                                        </span>
                                    </div>

                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.process_cells') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->getProcessCellLabelAttribute() }}
                                        </span>
                                    </div>

                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.erp_warehouse') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->warehouse_erp->name ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </x-ui.info-card>
                        </x-tabs.content>
                    @else
                        <x-tabs.content id="review_partly" :active="true">
                            {{-- Основна інформація --}}
                            <x-ui.info-card
                                :title="__('localization.inventory.view.review.main_info')"
                            >
                                <div class="row mx-0 p-0 gy-1">
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.type') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ __('localization.inventory.view.review.partial') }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.start_datetime') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->start_date?->format('Y.m.d H:i') ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex d-none">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.terminal_stock') }}
                                        </span>
                                        <span class="col-6 d-flex align-items-center">
                                            <spa
                                                class="rounded-circle bg-success me-25 d-inline-block"
                                                style="width: 10px; height: 10px"
                                            ></spa>
                                            @if ($inventory->show_leftovers)
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.show') }}
                                                </span>
                                            @else
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.hide') }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="col-6 d-flex align-items-center">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.executors') }}
                                        </span>
                                        <span
                                            class="col-6 d-flex align-items-center flex-wrap gap-50"
                                        >
                                            @forelse ($inventory->performers as $p)
                                                <span
                                                    class="border rounded p-25 d-inline-flex align-items-center me-50 mb-50"
                                                >
                                                    <img
                                                        src="https://ui-avatars.com/api/?name={{ rawurlencode($p->initial() ?? '') }}&size=24"
                                                        class="rounded-circle me-25"
                                                        width="24"
                                                        height="24"
                                                        alt="{{ $p->initial() }}"
                                                    />
                                                    {{ $p->initial() }}
                                                </span>
                                            @empty
                                                <em class="text-muted">—</em>
                                            @endforelse
                                        </span>
                                    </div>

                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.end_datetime') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->end_date?->format('Y.m.d H:i') ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex d-none">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.movement') }}
                                        </span>
                                        <span class="col-6 d-flex align-items-center">
                                            <span
                                                class="rounded-circle bg-success me-25 d-inline-block"
                                                style="width: 10px; height: 10px"
                                            ></span>
                                            @if ($inventory->restrict_goods_movement)
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.stop') }}
                                                </span>
                                            @else
                                                <span class="fw-bolder">
                                                    {{ __('localization.inventory.view.review.no_stop') }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="col-6 d-flex align-items-center">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.priority') }}
                                        </span>
                                        <div
                                            id="priority_full_dropdown"
                                            class="priority-container col-6 flex-wrap gap-25"
                                        >
                                            @foreach (range(0, 10) as $i)
                                                <button
                                                    type="button"
                                                    disabled
                                                    class="priority-btn {{ $i === $inventory->priority ? 'active show' : '' }}"
                                                    data-value="{{ $i }}"
                                                >
                                                    {{ $i }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.review.comment') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->comment }}
                                        </span>
                                    </div>
                                </div>
                            </x-ui.info-card>

                            {{-- Місце проведення --}}
                            <x-ui.info-card :title="__('localization.inventory.view.place.title')">
                                <div class="row mx-0 p-0 gy-1">
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.warehouse') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->warehouse?->name }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.row') }}
                                        </span>
                                        @if (! $inventory->row_id)
                                            <span class="col-6 fw-bold">
                                                {{ __('localization.inventory.view.all') }}
                                            </span>
                                        @else
                                            <span class="col-6 fw-bold">
                                                {{ $inventory->row?->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.erp_warehouse') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->warehouse_erp->name ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.cell_range') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->cell?->code ?: 'Усі' }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.zone') }}
                                        </span>
                                        @if ($inventory->zone_id)
                                            {{ $inventory->zone?->name }}
                                        @else
                                            <span class="col-6 fw-bold">
                                                {{ __('localization.inventory.view.all') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.process_cells') }}
                                        </span>
                                        <span class="col-6 fw-bold">
                                            {{ $inventory->getProcessCellLabelAttribute() }}
                                        </span>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="me-2 col-6">
                                            {{ __('localization.inventory.view.place.sector') }}
                                        </span>
                                        @if ($inventory->sector_id)
                                            {{ $inventory->sector?->name }}
                                        @else
                                            <span class="col-6 fw-bold">
                                                {{ __('localization.inventory.view.all') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </x-ui.info-card>

                            @if ($inventory->process_cell === 3)
                                {{-- Номенклатура --}}
                                <x-ui.info-card
                                    :title="__('localization.inventory.view.nomenclature.title')"
                                >
                                    <div class="row mx-0 p-0 gy-1">
                                        <div class="col-6 d-flex">
                                            <span class="me-2 col-6">
                                                {{ __('localization.inventory.view.nomenclature.category') }}
                                            </span>
                                            @if ($inventory->category_subcategory)
                                                @if ($inventory->category?->name)
                                                    {{ $inventory->category?->name }}
                                                @else
                                                        -
                                                @endif
                                            @else
                                                <span class="col-6 fw-bold">
                                                    {{ __('localization.inventory.view.all') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="me-2 col-6">
                                                {{ __('localization.inventory.view.nomenclature.supplier') }}
                                            </span>
                                            @if ($inventory->supplier_id)
                                                {{ $inventory->supplier?->fullName }}
                                            @else
                                                <span class="col-6 fw-bold">
                                                    {{ __('localization.inventory.view.all') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="me-2 col-6">
                                                {{ __('localization.inventory.view.nomenclature.manufacturer') }}
                                            </span>
                                            @if ($inventory->manufacturer_id)
                                                {{ $inventory->manufacturer?->fullName }}
                                            @else
                                                <span class="col-6 fw-bold">
                                                    {{ __('localization.inventory.view.all') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-6 d-flex align-items-center">
                                            <span class="me-2 col-6">
                                                {{ __('localization.inventory.view.nomenclature.nomenclature') }}
                                            </span>
                                            <div class="col-6 fw-bold">
                                                @php
                                                    $pivotRecords = DB::table('inventory_goods')
                                                        ->where('inventory_id', $inventory->id)
                                                        ->get();
                                                    $allGoodsSelected = $pivotRecords->contains(fn ($p) => is_null($p->goods_id));
                                                    $goods = $inventory->goods;
                                                @endphp

                                                @if ($allGoodsSelected)
                                                    {{ __('localization.inventory.view.all') }}
                                                @elseif (isset($goods) && $goods->isNotEmpty())
                                                    @foreach ($goods as $good)
                                                        <div
                                                            class="border rounded me-1 p-25"
                                                            style="
                                                                margin-bottom: 10px !important;
                                                                width: fit-content;
                                                                display: inline-block;
                                                            "
                                                        >
                                                            {{ $good->name }}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="me-2 col-6">
                                                {{ __('localization.inventory.view.nomenclature.brand') }}
                                            </span>
                                            <span class="col-6 fw-bold">
                                                {{ $inventory->brandData?->fullName ?? __('localization.inventory.view.all') }}
                                            </span>
                                        </div>
                                    </div>
                                </x-ui.info-card>
                            @endif
                        </x-tabs.content>
                    @endif

                    <x-tabs.content id="venue" :active="false">
                        @php
                            $barData = $inventory->getItemsData();
                            $total = (int) ($barData['total'] ?? 0);
                            $finished = (int) ($barData['finished'] ?? 0);
                            $percent = (int) round((float) ($barData['percent'] ?? 0));
                            $percent = max(0, min(100, $percent));
                        @endphp

                        {{-- @if ($statusId !== Inventory::STATUS_CREATED) --}}
                        <x-layout.index-table-card
                            :title="__('localization.inventory.view.place.title')"
                            tableId="inventory-venue-table"
                            idOne="settingTable-venue"
                            idTwo="changeFonts-venue"
                            idThree="changeCol-venue"
                            idFour="jqxlistbox-venue"
                            class="mx-0"
                        >
                            @if ($statusId !== Inventory::STATUS_FINISHED)
                                <div
                                    class="d-flex flex-row gap-1 flex-wrap align-items-center w-25"
                                >
                                    <div class="d-flex justify-content-between flex-grow-1">
                                        <span class="small fw-bolder">
                                            {{
                                                strtr(__('localization.inventory.view.an_animal.cells_progress'), [
                                                    ':completed' => $finished,
                                                    ':total' => $total,
                                                ])
                                            }}
                                        </span>
                                        <span class="small">{{ $percent }}%</span>
                                    </div>
                                    <div class="progress w-100" style="height: 12px">
                                        <div
                                            class="progress-bar bg-warning"
                                            role="progressbar"
                                            aria-valuenow="{{ $percent }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: {{ $percent }}%"
                                        ></div>
                                    </div>
                                </div>
                            @endif
                        </x-layout.index-table-card>
                        {{-- @endif --}}
                    </x-tabs.content>

                    <x-tabs.content id="an_animal" :active="false">
                        @if ($statusId === Inventory::STATUS_CREATED)
                            <x-card.nested>
                                <x-slot:body>
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center height-500"
                                    >
                                        <h2>
                                            {{ __('localization.inventory.view.an_animal.results') }}
                                        </h2>
                                        <p>
                                            {{ __('localization.inventory.view.an_animal.empty_text') }}
                                        </p>
                                    </div>
                                </x-slot>
                            </x-card.nested>
                        @endif

                        @if ($statusId !== Inventory::STATUS_CREATED)
                            <x-layout.index-table-card
                                :title="__('localization.inventory.view.tabs.an_animal')"
                                tableId="inventory-an-animal-table"
                                idOne="settingTable-base"
                                idTwo="changeFonts-base"
                                idThree="changeCol-base"
                                idFour="jqxlistbox-base"
                                class="mx-0"
                            />
                        @endif
                    </x-tabs.content>

                    <x-tabs.content id="an_animal_erp" :active="false">
                        {{-- @if ($statusId !== Inventory::STATUS_IN_PROGRESS) --}}
                        <x-card.nested>
                            <x-slot:body>
                                {{-- @if ($statusId === Inventory::STATUS_CREATED) --}}
                                <div
                                    class="d-flex flex-column align-items-center justify-content-center height-500"
                                >
                                    <h2>
                                        {{ __('localization.inventory.view.an_animal_erp.compare') }}
                                    </h2>
                                    <p>
                                        {{ __('localization.inventory.view.an_animal_erp.text') }}
                                    </p>
                                </div>
                                {{-- @endif --}}

                                @if ($statusId === Inventory::STATUS_IN_PROGRESS_ANIMAL)
                                    <div
                                        class="d-none flex-column align-items-center justify-content-center height-500"
                                    >
                                        <h2>
                                            {{ __('localization.inventory.view.an_animal_erp.compare') }}
                                        </h2>
                                        <button class="btn btn-primary">
                                            {{ __('localization.inventory.view.an_animal_erp.button') }}
                                        </button>
                                    </div>
                                @endif
                            </x-slot>
                        </x-card.nested>
                        {{-- @endif --}}

                        @if ($statusId === Inventory::STATUS_IN_PROGRESS)
                            <x-layout.index-table-card
                                :title="__('localization.inventory.view.tabs.an_animal_erp')"
                                tableId="inventory-an-animal-erp-table"
                                idOne="settingTable-erp"
                                idTwo="changeFonts-erp"
                                idThree="changeCol-erp"
                                idFour="jqxlistbox-erp"
                                class="mx-0 d-none"
                            />
                        @endif
                    </x-tabs.content>
                @endslot
            </x-tabs.group>
        </x-slot>
    </x-layout.container>

    <!-- Delete warehouse modal -->
    <x-modal.delete-modal
        modalId="deleteModalCenter"
        :action="route('inventory.destroy', ['inventory' => $inventory])"
        title="localization.inventory.view.modal_delete.title"
        description="localization.inventory.view.modal_delete.confirmation"
        cancelText="localization.inventory.view.modal_delete.cancel"
        confirmText="localization.inventory.view.modal_delete.submit"
    />

    <x-modal.base id="target" class="js-table-popover-modal" size="modal-fullscreen">
        <x-slot name="header">
            <div class="d-flex flex-grow-1 justify-content-between gap-1 align-items-center">
                <div class="d-flex flex-column gap-50 col-4">
                    <div class="fw-bold">
                        {{ __('localization.inventory.view.modals.leftovers') }}
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <!-- Назва комірки -->
                        <div id="cell-name" class="mb-0 fw-bolder fs-4 w-auto">—</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1">
                    @if ($statusId === 2 || $statusId === Inventory::STATUS_PENDING)
                        <button type="button" class="btn btn-primary" id="target_submit">
                            {{ __('localization.inventory.view.modals.buttons.confirm') }}
                        </button>
                        <div class="vr mx-0 my-25 bg-secondary-subtle"></div>
                    @endif

                    <button
                        class="btn btn-flat-secondary p-1"
                        data-bs-dismiss="modal"
                        aria-label="{{ __('localization.inventory.view.modals.buttons.cancel') }}"
                    >
                        <i data-feather="x"></i>
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="card-grid" style="position: relative">
                @include(
                    'layouts.table-setting',
                    [
                        'idOne' => 'settingTable-venue-an-animal',
                        'idTwo' => 'changeFonts-venue-an-animal',
                        'idThree' => 'changeCol-venue-an-animal',
                        'idFour' => 'jqxlistbox-venue-an-animal',
                    ]
                )

                <!-- Таблиця -->
                <div class="table-block d-none" id="inventory-venue-an-animal-table"></div>
            </div>

            <!-- Порожній блок -->
            <div
                id="empty-cell-block"
                class="px-1 bg-secondary-100 d-flex flex-grow-1 flex-column gap-1 justify-content-center rounded align-items-center h-100 text-dark p-1 d-none"
            >
                <h3 id="placeholder_leftovers" class="d-block">
                    {{ __('localization.inventory.view.modals.empty_cell') }}
                </h3>

                @if ($statusId === 2 || $statusId === Inventory::STATUS_PENDING)
                    <x-modal.modal-trigger-button
                        id="add_leftovers_button_empty"
                        target="add_leftovers"
                        class="btn btn-outline-secondary d-block"
                        :text="__('localization.inventory.view.modals.add_leftovers_button')"
                        icon="plus"
                    />
                @endif
            </div>
        </x-slot>

        @if ($statusId === 2 || $statusId === Inventory::STATUS_PENDING)
            <x-slot name="footer">
                <x-modal.modal-trigger-button
                    id="add_leftovers_button_footer"
                    target="add_leftovers"
                    class="btn btn-outline-secondary d-block"
                    :text="__('localization.inventory.view.modals.add_leftovers_button')"
                    icon="plus"
                />
            </x-slot>
        @endif
    </x-modal.base>

    @if ($statusId === 2 || $statusId === Inventory::STATUS_PENDING)
        <x-modal.base id="correction_quantity" size="modal-fullscreen">
            <x-slot name="header">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.inventory.view.correction_quantity.title') }}
                </x-ui.section-card-title>
            </x-slot>

            <x-slot name="body">
                <div id="count_of_leftovers" class="px-1 mb-1">
                    {{ __('localization.inventory.view.correction_quantity.label_count') }}
                    <span id="leftovers-quantity">-</span>
                </div>

                <x-form.select
                    id="available_packages"
                    name="available_packages"
                    :label="__('localization.inventory.view.correction_quantity.available_packages')"
                    :placeholder="__('localization.inventory.view.correction_quantity.available_packages')"
                    class="col-12 mb-1 px-1"
                />

                <x-form.input-text
                    id="count_of_available_packages"
                    name="count_of_available_packages"
                    :label="__('localization.inventory.view.correction_quantity.count_of_available_packages')"
                    :placeholder="__('localization.inventory.view.correction_quantity.count_of_available_packages')"
                    class="col-12 px-1"
                    oninput="limitInputToNumbers(this, 10)"
                />
            </x-slot>

            <x-slot name="footer">
                <button
                    type="button"
                    class="btn btn-link"
                    data-bs-target="#correction_quantity"
                    data-bs-toggle="modal"
                    data-dismiss="modal"
                >
                    {{ __('localization.inventory.view.correction_quantity.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="package_submit">
                    {{ __('localization.inventory.view.correction_quantity.apply') }}
                </button>
            </x-slot>
        </x-modal.base>

        <x-modal.base id="add_leftovers" size="modal-fullscreen">
            <x-slot name="header">
                <div
                    class="d-flex px-50 flex-grow-1 justify-content-center position-relative gap-1 align-items-center"
                >
                    <x-ui.section-card-title level="2" class="modal-title">
                        {{ __('localization.inventory.view.modals.add_leftovers.title') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0">
                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __('localization.inventory.view.modals.add_leftovers.product_params') }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="goods_id"
                        name="goods_id"
                        :label="__('localization.leftovers.form.goods')"
                        :placeholder="__('localization.leftovers.form.goods_placeholder')"
                        data-dictionary="goods"
                        class="col-12 mb-1"
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
                            {{ __('localization.inventory.view.modals.add_leftovers.condition.option_dmg') }}
                        </option>
                        <option value="true">
                            {{ __('localization.inventory.view.modals.add_leftovers.condition.option_no_dmg') }}
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
                        {{ __('localization.inventory.view.modals.add_leftovers.quantity_section') }}
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
                        {{ __('localization.inventory.view.modals.add_leftovers.placement') }}
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
                    {{ __('localization.inventory.view.modals.buttons.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="add_leftovers_submit">
                    {{ __('localization.inventory.view.modals.buttons.confirm') }}
                </button>
            </x-slot>
        </x-modal.base>

        <x-modal.base id="edit_leftovers" size="modal-fullscreen">
            <x-slot name="header">
                <div
                    class="d-flex px-50 flex-grow-1 justify-content-center position-relative gap-1 align-items-center"
                >
                    <x-ui.section-card-title level="2" class="modal-title">
                        {{ __('localization.inventory.view.modals.edit_leftovers.title') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0">
                    <x-ui.section-card-title level="5" class="modal-title mb-1">
                        {{ __('localization.inventory.view.modals.add_leftovers.product_params') }}
                    </x-ui.section-card-title>

                    <x-form.select
                        id="edit_goods_id"
                        name="edit_goods_id"
                        :label="__('localization.leftovers.form.goods')"
                        :placeholder="__('localization.leftovers.form.goods_placeholder')"
                        data-dictionary="goods"
                        class="col-12 mb-1"
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
                            {{ __('localization.inventory.view.modals.add_leftovers.condition.option_dmg') }}
                        </option>
                        <option value="true">
                            {{ __('localization.inventory.view.modals.add_leftovers.condition.option_no_dmg') }}
                        </option>
                    </x-form.select>

                    <x-form.select
                        id="edit_expiration_term"
                        name="edit_expiration_term"
                        :label="__('localization.inventory.view.modals.add_leftovers.expiration.label')"
                        :placeholder="__('localization.inventory.view.modals.add_leftovers.expiration.placeholder')"
                        data-dependent="edit_goods_id"
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
                        {{ __('localization.inventory.view.modals.add_leftovers.quantity_section') }}
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
                        {{ __('localization.inventory.view.modals.add_leftovers.placement') }}
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
                    {{ __('localization.inventory.view.modals.buttons.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="edit_leftovers_submit">
                    {{ __('localization.inventory.view.modals.buttons.confirm') }}
                </button>
            </x-slot>
        </x-modal.base>
    @endif

    {{-- <x-modal.base id="cancel" size="modal-md"> --}}
    {{-- <x-slot name="header"> --}}
    {{-- <x-ui.section-card-title level="4" class="modal-title"> --}}
    {{-- {{ __('localization.inventory.view.modals.cancel.title', ['id' => $inventory->local_id]) }} --}}
    {{-- </x-ui.section-card-title> --}}
    {{-- </x-slot> --}}

    {{-- <x-slot name="body"> --}}
    {{-- <div class="px-75"> --}}
    {{-- {{ __('localization.inventory.view.modals.cancel.text') }} --}}
    {{-- </div> --}}
    {{-- </x-slot> --}}

    {{-- <x-slot name="footer"> --}}
    {{-- <button type="button" class="btn btn-link" data-bs-dismiss="modal"> --}}
    {{-- {{ __('localization.inventory.view.modals.buttons.cancel_action') }} --}}
    {{-- </button> --}}
    {{-- <button type="button" class="btn btn-danger" id="cancel_submit"> --}}
    {{-- {{ __('localization.inventory.view.modals.cancel.confirm_button') }} --}}
    {{-- </button> --}}
    {{-- </x-slot> --}}
    {{-- </x-modal.base> --}}

    <x-modal.base id="end" size="modal-md">
        <x-slot name="header">
            <x-ui.section-card-title level="4" class="modal-title">
                @php
                    $data = $inventory->getItemsData();
                    $progress = (int) ($barData['progress'] ?? 0);

                    $cellWord = \App\Helpers\PluralHelper::word($progress, [
                        __('localization.inventory.view.modals.end.cell_one'),
                        __('localization.inventory.view.modals.end.cell_few'),
                        __('localization.inventory.view.modals.end.cell_many'),
                    ]);

                    $countText = "<strong>{$progress}</strong> {$cellWord}";
                @endphp

                {{ __('localization.inventory.view.modals.end.title', ['id' => $inventory->local_id]) }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="px-50">
                @if ($progress > 0)
                    {!!
                        __('localization.inventory.view.modals.end.text_warning', [
                            'count' => $countText,
                        ])
                    !!}
                @else
                    {{ __('localization.inventory.view.modals.end.text_success') }}
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            @if ($progress > 0)
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">
                    {{ __('localization.inventory.view.modals.buttons.continue_inventory') }}
                </button>
            @else
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">
                    {{ __('localization.inventory.view.modals.buttons.back') }}
                </button>
            @endif

            <a href="{{ route('inventory.finish_before', $inventory) }}">
                <button class="btn {{ $progress > 0 ? 'btn-danger' : 'btn-primary' }}">
                    {{ __('localization.inventory.view.modals.end.confirm_button') }}
                </button>
            </a>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        @if ($statusId !== Inventory::STATUS_CREATED)

        tableSetting($('#inventory-an-animal-table'), '-base', 100, 150);
        tableSetting($('#inventory-an-animal-erp-table'), '-erp', 100, 150);
        @endif

        tableSetting($('#inventory-venue-table'), '-venue', 100, 150);
        tableSetting($('#inventory-venue-an-animal-table'), '-venue-an-animal', 150, 200);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        @if ($statusId !== Inventory::STATUS_CREATED)

        offCanvasByBorder($('#inventory-an-animal-table'), '-base');
        offCanvasByBorder($('#inventory-an-animal-erp-table'), '-erp');
        @endif

        offCanvasByBorder($('#inventory-venue-table'), '-venue');
        offCanvasByBorder($('#inventory-venue-an-animal-table'), '-venue-an-animal');
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
        src="{{ asset('assets/js/utils/dictionary/selectDictionaryRelated.js') }}"
    ></script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/inventory/inventory-leftovers.js') }}"
    ></script>

    <script>
        const containerDropdown = document.getElementById('priority_full_dropdown');
        const buttonsDropdown = containerDropdown.querySelectorAll('.priority-btn');

        buttonsDropdown.forEach((btn) => {
            btn.addEventListener('click', () => {
                const isExpanded = containerDropdown.classList.contains('expanded');
                const activeButton = containerDropdown.querySelector('.priority-btn.active');
                const currentValue = activeButton ? activeButton.dataset.value : null;
                const clickedValue = btn.dataset.value;

                // Якщо список згорнутий — розгортаємо всі кнопки
                if (!isExpanded) {
                    containerDropdown.classList.add('expanded');
                    buttonsDropdown.forEach((b) => (b.style.display = 'inline-block'));
                    return;
                }

                // Якщо натиснули на той самий пріоритет — просто згортаємо назад
                if (isExpanded && currentValue === clickedValue) {
                    containerDropdown.classList.remove('expanded');
                    buttonsDropdown.forEach((b) => (b.style.display = 'none'));
                    btn.style.display = 'inline-block';
                    return;
                }

                // Інакше — змінюємо активну кнопку
                buttonsDropdown.forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');

                console.log('Вибраний пріоритет:', clickedValue);

                // Згортаємо список — залишаємо тільки вибрану кнопку
                containerDropdown.classList.remove('expanded');
                buttonsDropdown.forEach((b) => (b.style.display = 'none'));
                btn.style.display = 'inline-block';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
            const url = new URL(window.location);

            // 1️⃣ Якщо у посиланні вже є ?tab=...
            const currentTab = url.searchParams.get('tab');
            if (currentTab) {
                const tabElement = document.querySelector(`.nav-link[href="#${currentTab}"]`);
                if (tabElement) {
                    const bsTab = new bootstrap.Tab(tabElement);
                    bsTab.show();
                }
            }

            // 2️⃣ При перемиканні таби оновлюємо URL
            tabs.forEach((tab) => {
                tab.addEventListener('shown.bs.tab', (event) => {
                    const tabId = event.target.getAttribute('href').replace('#', '');
                    url.searchParams.set('tab', tabId);
                    window.history.replaceState({}, '', url);
                });
            });
        });
    </script>

    <script src="{{ asset('assets/js/entity/leftovers/utils/expiration-calculator.js') }}"></script>
@endsection
