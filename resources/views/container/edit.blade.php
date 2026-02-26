@extends('layouts.admin')
@section('title', __('localization.container_edit_title'))
@section('page-style')
    
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
                            'name' => __('localization.container_edit_breadcrumb_container'),
                        ],
                        [
                            'url' => '/containers/' . $container->id,
                            'name' => __('localization.container_edit_breadcrumb_view_container', [
                                'name' => $container->name,
                            ]),
                        ],
                        [
                            'name' => __('localization.container_edit_breadcrumb_edit_container', [
                                'name' => $container->name,
                            ]),
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
            <x-page-title :title="__('localization.container_edit_title_card')" />

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.container_edit_basic_data') }}
                    </x-section-title>
                </x-slot>
                <x-slot:body>
                    <x-form.input-text
                        id="name"
                        name="name"
                        label="localization.container_create_name"
                        placeholder="localization.container_create_name_placeholder"
                        value="{{ $container->name }}"
                    />

                    <x-form.select
                        id="type_id"
                        name="type_id"
                        label="localization.container_edit_container_type"
                        placeholder="localization.container_edit_select_container_type"
                        data-dictionary="container_type"
                        data-id="{{ $container->type_id }}"
                    ></x-form.select>

                    <x-form.input-text
                        id="code_format"
                        name="code_format"
                        label="localization.container_edit_unique_number"
                        placeholder="localization.container_edit_unique_number_placeholder"
                        oninput="limitInputToCodeFormat(this, 5)"
                        value="{{ $container->code_format }}"
                    />

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.checkbox
                        id="reversible"
                        name="reversible"
                        :checked="$container->reversible"
                        label="localization.container_edit_reversible"
                        :inline="true"
                    />

                    <div class="mt-1" id="base-error"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.container_edit_parameters_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.input-text-with-unit
                        id="weight"
                        name="weight"
                        label="localization.container_edit_weight"
                        placeholder="localization.container_edit_weight_placeholder"
                        unit="localization.container_edit_weight_unit"
                        oninput="maskFractionalNumbers(this,5)"
                        value="{{ $container->weight }}"
                    />

                    <x-form.input-text-with-unit
                        id="max_weight"
                        name="max_weight"
                        label="localization.container_create_max_weight_label"
                        placeholder="localization.container_create_max_weight_placeholder"
                        unit="localization.container_create_weight_unit"
                        oninput="maskFractionalNumbers(this,5)"
                        value="{{ $container->max_weight }}"
                    />

                    <x-form.input-group-wrapper wrapperClass="col-12 col-md-12 col-lg-6 px-0 mb-0">
                        <x-form.input-text-with-unit
                            id="height"
                            name="height"
                            label="localization.container_edit_height"
                            placeholder="localization.container_edit_height_placeholder"
                            unit="localization.container_edit_height_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            value="{{ $container->height }}"
                            class="col-12 col-md-4 mb-1 mb-md-0"
                        />

                        <x-form.input-text-with-unit
                            id="width"
                            name="width"
                            label="localization.container_edit_width"
                            placeholder="localization.container_edit_width_placeholder"
                            unit="localization.container_edit_width_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            value="{{ $container->width }}"
                            class="col-12 col-md-4 mb-1 mb-md-0"
                        />

                        <x-form.input-text-with-unit
                            id="length"
                            name="length"
                            label="localization.container_edit_depth"
                            placeholder="localization.container_edit_depth_placeholder"
                            unit="localization.container_edit_depth_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            value="{{ $container->length }}"
                            class="col-12 col-md-4"
                        />
                    </x-form.input-group-wrapper>

                    <div class="mt-1" id="parameters-error"></div>
                </x-slot>
            </x-card.nested>

            <x-ui.action-button
                id="edit-container"
                class="btn btn-primary mb-2 float-end"
                :text="__('localization.container_edit_save')"
            />
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancel_add_containers"
        route="/containers"
        title="{{ __('localization.container_cancel_edit_modal_title') }}"
        content="{!! __('localization.container_cancel_edit_modal_content') !!}"
        cancel-text="{{ __('localization.container_cancel_edit_modal_cancel_button') }}"
        confirm-text="{{ __('localization.container_cancel_edit_modal_confirm_button') }}"
    />
@endsection

@section('page-script')
    <script>
        const container = {!! json_encode($container) !!};
    </script>

    <script type="module" src="{{ asset('assets/js/entity/container/container.js') }}"></script>
@endsection
