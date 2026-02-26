@extends('layouts.admin')
@section('title', __('localization.container_create_title'))
@section('before-style')
    
@endsection

@section('content')
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/containers',
                            'name' => __('localization.container_create_breadcrumbs_container'),
                        ],
                        [
                            'name' => __('localization.container_create_breadcrumbs_add_container'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel_add_containers"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-page-title :title="__('localization.container_create_breadcrumbs_add_container')" />

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.container_create_base_data') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.input-text
                        id="name"
                        name="name"
                        label="localization.container_create_name"
                        placeholder="localization.container_create_name_placeholder"
                    />

                    <x-form.select
                        id="type_id"
                        name="type_id"
                        label="localization.container_create_container_type_label"
                        placeholder="localization.container_create_container_type_placeholder"
                        data-dictionary="container_type"
                    ></x-form.select>

                    <x-form.input-text
                        id="code_format"
                        name="code_format"
                        label="localization.container_create_unique_number_label"
                        placeholder="localization.container_create_unique_number_placeholder"
                        oninput="limitInputToCodeFormat(this, 5)"
                    />

                    <div class="col-6 px-0 mb-1 hidden d-md-block"></div>

                    <x-form.checkbox
                        id="reversible"
                        name="reversible"
                        :checked="true"
                        label="localization.container_create_reversible_label"
                        :inline="true"
                    />

                    <div class="mt-1" id="base-error"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.container_create_parameters_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.input-text-with-unit
                        id="weight"
                        name="weight"
                        label="localization.container_create_weight_label"
                        placeholder="localization.container_create_weight_placeholder"
                        unit="localization.container_create_weight_unit"
                        oninput="maskFractionalNumbers(this,5)"
                    />

                    <x-form.input-text-with-unit
                        id="max_weight"
                        name="max_weight"
                        label="localization.container_create_max_weight_label"
                        placeholder="localization.container_create_max_weight_placeholder"
                        unit="localization.container_create_weight_unit"
                        oninput="maskFractionalNumbers(this,5)"
                    />

                    <x-form.input-group-wrapper wrapperClass="col-12 col-md-12 col-lg-6 px-0 mb-0">
                        <x-form.input-text-with-unit
                            id="height"
                            name="height"
                            label="localization.container_create_height_label"
                            placeholder="localization.container_create_height_placeholder"
                            unit="localization.container_create_height_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            class="col-12 col-md-4 mb-1 mb-md-0"
                        />

                        <x-form.input-text-with-unit
                            id="width"
                            name="width"
                            label="localization.container_create_width_label"
                            placeholder="localization.container_create_width_placeholder"
                            unit="localization.container_create_width_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            class="col-12 col-md-4 mb-1 mb-md-0"
                        />

                        <x-form.input-text-with-unit
                            id="length"
                            name="length"
                            label="localization.container_create_depth_label"
                            placeholder="localization.container_create_depth_placeholder"
                            unit="localization.container_create_depth_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            class="col-12 col-md-4"
                        />
                    </x-form.input-group-wrapper>

                    <div class="mt-1" id="parameters-error"></div>
                </x-slot>
            </x-card.nested>

            <x-ui.action-button
                id="create-container"
                class="btn btn-primary mb-2 float-end"
                :text="__('localization.container_create_save')"
            />
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancel_add_containers"
        route="/containers"
        title="{{ __('localization.container_cancel_create_modal_title') }}"
        content="{!! __('localization.container_cancel_create_modal_content') !!}"
        cancel-text="{{ __('localization.container_cancel_edit_modal_cancel_button') }}"
        confirm-text="{{ __('localization.container_cancel_edit_modal_confirm_button') }}"
    />
@endsection

@section('page-script')
    <script type="module" src="{{ asset('assets/js/entity/container/container.js') }}"></script>
@endsection
