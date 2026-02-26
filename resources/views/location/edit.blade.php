@extends('layouts.admin')
@section('title', __('localization.location.edit.title'))

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"
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

@section('content')
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/locations',
                            'name' => __('localization.location.edit.breadcrumb.warehouses'),
                        ],
                        [
                            'url' => '/locations/' . $location->id,
                            'name' => __('localization.location.edit.breadcrumb.view_warehouse', [
                                'name' => $location->name,
                            ]),
                        ],
                        [
                            'name' => __('localization.location.edit.breadcrumb.edit_warehouse'),
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
            <x-page-title :title="__('localization.location.edit.breadcrumb.edit_warehouse')" />

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
                        value="{{ $location->name }}"
                        label="localization.location.create.block_1.name_label"
                        placeholder="localization.location.create.block_1.name_placeholder"
                    />

                    <x-form.select
                        id="company_id"
                        name="company_id"
                        label="localization.location.create.block_1.company_label"
                        placeholder="localization.location.create.block_1.company_placeholder"
                        data-dictionary="company"
                        data-id="{{ $location->company_id }}"
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
                        data-id="{{ $location->country_id }}"
                    ></x-form.select>

                    <x-form.select
                        id="settlement_id"
                        name="settlement_id"
                        label="localization.location.create.block_1.settlement_label"
                        placeholder="localization.location.create.block_1.settlement_placeholder"
                        data-dictionary="settlement"
                        data-id="{{ $location->settlement_id }}"
                    ></x-form.select>

                    <x-form.input-text
                        id="street_id"
                        name="street_id"
                        label="localization.location.create.block_1.street_label"
                        placeholder="localization.location.create.block_1.street_placeholder"
                        value="{{ $location->street_info['title'] }}"
                    />

                    <x-form.input-text
                        id="building_number"
                        name="building_number"
                        label="localization.location.create.block_1.building_number_label"
                        placeholder="localization.location.create.block_1.building_number_placeholder"
                        value="{{ $location->street_info['building'] }}"
                    />

                    <x-form.input-text
                        id="map-input"
                        name="map-input"
                        label="localization.location.create.block_2.coordinates_label"
                        placeholder="localization.location.create.block_2.coordinates_placeholder"
                        required
                        class="col-12 mb-1"
                        value="{{ $location->url }}"
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
                    id="update"
                    class="btn btn-primary mb-2"
                    :text="__('localization.location.edit.save_button')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancel"
        route="/locations/{{ $location->id }}"
        title="{{ __('localization.location.edit.cancel_modal.title') }}"
        content="{!! __('localization.location.edit.cancel_modal.message') !!}"
        cancel-text="{{ __('localization.location.edit.cancel_modal.cancel_button') }}"
        confirm-text="{{ __('localization.location.edit.cancel_modal.confirm_button') }}"
    />
@endsection

@section('page-script')
    <script>
        var marker = null;
        var coordinates = {!! json_encode($location->url) !!};
        var locations = {!! json_encode($location) !!};
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
