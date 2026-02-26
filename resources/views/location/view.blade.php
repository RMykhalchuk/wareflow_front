@extends('layouts.admin')
@section('title', __('localization.location.view.title'))

@section('page-style')
    
@endsection

@section('before-style')
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
                            'name' => __('localization.location.view.breadcrumb.warehouses'),
                        ],
                        [
                            'name' => __('localization.location.view.breadcrumb.current'),
                            'name2' => $location->name ?? __('localization.location.view.no_name'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-ui.icon-link-button
                    href="{{ route('locations.edit', ['location' => $location->id]) }}"
                    :title="__('localization.location.view.edit_icon_tooltip')"
                    icon="edit"
                />

                <x-ui.icon-dropdown
                    id="header-dropdown"
                    menuClass="d-flex flex-column justify-content-center px-1"
                >
                    <x-modal.modal-trigger-button
                        id="cancel_button"
                        target="delete-modal"
                        class="btn btn-flat-danger"
                        :text="__('localization.location.view.modal_delete_confirm')"
                    />
                </x-ui.icon-dropdown>
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested>
                <x-slot:header>
                    <div class="d-flex flex-column px-1">
                        <x-ui.section-card-title level="4">
                            {{ $location->name }}
                        </x-ui.section-card-title>

                        <a
                            href="{{ route('companies.show', ['company' => $location->company->id]) }}"
                            style="color: #4b465c"
                        >
                            <p style="text-decoration: underline">
                                {{ $location->company->company->name ?? $location->company->company->surname . ' ' . $location->company->company->first_name . ' ' . $location->company->company->patronymic }}
                            </p>
                        </a>
                    </div>
                </x-slot>

                <x-slot:body>
                    <x-ui.section-divider class="mt-1 mb-2" />

                    <x-ui.col-row-wrapper>
                        <x-ui.section-card-title level="5">
                            {{ __('localization.location.view.main_data') }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <x-card.card-data-row
                                :label="__('localization.location.view.address')"
                                value="{!! sprintf('%s %s %s %s',
                                            $location->street_info['title'] ?? '',
                                            $location->street_info['building'] ?? '',
                                            $location->settlement->name ?? '',
                                            $location->country->name ?? ''
                                        ) !!}"
                            />

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15"></div>
                                <div class="col-auto col-md-9 f-15 pe-0">
                                    <div id="map" style="height: 270px; border-radius: 6px"></div>
                                </div>
                            </div>
                        </x-card.card-data-wrapper>
                    </x-ui.col-row-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    <!-- Delete warehouse modal -->
    <x-modal.delete-modal
        modalId="delete-modal"
        :action="route('locations.destroy', ['location' => $location->id])"
        title="localization.location.view.modal_delete.title"
        description="localization.location.view.modal_delete.confirmation"
        cancelText="localization.location.view.modal_delete.cancel"
        confirmText="localization.location.view.modal_delete_confirm"
        :use-button-instead-of-form="true"
        {{-- або false --}}
    />
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script src="{{ asset('js/scripts/maps/map-leaflet.js') }}"></script>
    <script src="{{ asset('vendors/js/maps/leaflet.min.js') }}"></script>

    <script>
        let coordinatesLoad = {!! json_encode($location->url) !!};
        let locations = {!! json_encode($location) !!};
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/location/locationWarehouseMapsView.js') }}"
    ></script>

    <script type="module" src="{{ asset('assets/js/entity/location/location.js') }}"></script>
@endsection
