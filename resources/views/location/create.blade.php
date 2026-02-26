@extends('layouts.admin')
@section('title', __('localization.location.create.title'))

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

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/maps/leaflet.min.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/maps/map-leaflet.css')) }}"
    />
@endsection

@section('content')
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/locations',
                            'name' => __('localization.location.create.breadcrumb.warehouses'),
                        ],
                        [
                            'name' => __('localization.location.create.breadcrumb.add'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-page-title :title="__('localization.location.create.page.title')" />

            <x-card.nested id="block_1">
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.location.create.block_1.main_data_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.input-text
                        id="name"
                        name="name"
                        label="localization.location.create.block_1.name_label"
                        placeholder="localization.location.create.block_1.name_placeholder"
                    />

                    <x-form.select
                        id="company_id"
                        name="company_id"
                        label="localization.location.create.block_1.company_label"
                        placeholder="localization.location.create.block_1.company_placeholder"
                        data-dictionary="company"
                    ></x-form.select>

                    <div class="mt-1" id="main-data-message"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested id="block_2">
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.location.create.block_2.title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.select
                        id="country_id"
                        name="country_id"
                        label="localization.location.create.block_1.country_label"
                        placeholder="localization.location.create.block_1.country_placeholder"
                        data-dictionary="country"
                    ></x-form.select>

                    <x-form.select
                        id="settlement_id"
                        name="settlement_id"
                        label="localization.location.create.block_1.settlement_label"
                        placeholder="localization.location.create.block_1.settlement_placeholder"
                        data-dictionary="settlement"
                    ></x-form.select>

                    <x-form.input-text
                        id="street_id"
                        name="street_id"
                        label="localization.location.create.block_1.street_label"
                        placeholder="localization.location.create.block_1.street_placeholder"
                    />

                    <x-form.input-text
                        id="building_number"
                        name="building_number"
                        label="localization.location.create.block_1.building_number_label"
                        placeholder="localization.location.create.block_1.building_number_placeholder"
                    />

                    <x-form.input-text
                        id="map-input"
                        name="map-input"
                        label="localization.location.create.block_2.coordinates_label"
                        placeholder="localization.location.create.block_2.coordinates_placeholder"
                        required
                        class="col-12 mb-1"
                    />

                    <div class="col-12">
                        <div id="map" class="rounded" style="visibility: hidden; height: 0"></div>
                    </div>

                    <div class="col-12 mt-1">
                        <div class="message_add" id="messageAdd"></div>
                        <div class="message_addError" id="messageAddError"></div>
                        <div id="address-message"></div>
                    </div>
                </x-slot>
            </x-card.nested>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="create"
                    class="btn btn-primary mb-2"
                    :text="__('localization.location.create.save_button')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancel"
        route="/locations"
        title="{{ __('localization.location.create.cancel_modal.title') }}"
        content="{!! __('localization.location.create.cancel_modal.confirmation') !!}"
        cancel-text="{{ __('localization.location.create.cancel_modal.cancel') }}"
        confirm-text="{{ __('localization.location.create.cancel_modal.submit') }}"
    />
@endsection

@section('page-script')
    <script>
        var marker = null;
        let coordinates = null;
    </script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script src="{{ asset('js/scripts/maps/map-leaflet.js') }}"></script>
    <script src="{{ asset('vendors/js/maps/leaflet.min.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/location/location.js') }}"></script>
    <script
        type="module"
        src="{{ asset('assets/js/entity/location/locationWarehouseMaps.js') }}"
    ></script>
@endsection
