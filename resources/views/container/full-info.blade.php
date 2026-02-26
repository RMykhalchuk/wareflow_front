@extends('layouts.admin')
@section('title', __('localization.container_view_title'))

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
                            'name' => __('localization.container_view_breadcrumb_container'),
                        ],
                        [
                            'name' => __('localization.container_view_breadcrumb_view_container', [
                                'name' => $container->name,
                            ]),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                {{-- <x-ui.icon-button --}}
                {{-- id="jsPrintBtn" --}}
                {{-- :title="__('localization.container_view_print')" --}}
                {{-- icon="printer" --}}
                {{-- /> --}}

                {{-- <x-ui.icon-button --}}
                {{-- id="jsCopyBtn" --}}
                {{-- :title="__('localization.container_view_copy')" --}}
                {{-- icon="copy" --}}
                {{-- /> --}}

                <x-ui.icon-link-button
                    href="{{ route('containers.edit', ['container' => $container->id]) }}"
                    :title="__('localization.container_view_edit')"
                    icon="edit"
                />

                <x-ui.icon-dropdown id="header-dropdown">
                    <x-ui.dropdown-item
                        href="#"
                        icon="info"
                        :text="__('localization.container_view_product_usage')"
                    />
                    <x-ui.dropdown-item
                        href="#"
                        icon="info"
                        :text="__('localization.container_view_move_pc_pallet')"
                    />
                    <x-ui.dropdown-item
                        href="#"
                        icon="info"
                        :text="__('localization.container_view_product_movement')"
                    />
                </x-ui.icon-dropdown>
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested id="jsCopyEventScope">
                <x-slot:header>
                    <div class="d-flex flex-column px-1">
                        <x-ui.section-card-title level="4">
                            {{ $container->name }}
                        </x-ui.section-card-title>

                        <p class="mb-0">{{ $container->code_format }}</p>
                    </div>
                </x-slot>

                <x-slot:body>
                    <x-ui.section-divider class="mt-1 mb-2" />

                    <x-ui.col-row-wrapper>
                        <x-ui.section-card-title level="5">
                            {{ __('localization.container_view_basic_data') }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <x-card.card-data-row :label="__('localization.container_view_type')">
                                <p class="fs-5 m-0 fw-medium-c">
                                    @switch($container->type->key ?? null)
                                        @case('type_1')
                                            {{ __('localization.container_view_type_1') }}

                                            @break
                                        @case('type_2')
                                            {{ __('localization.container_view_type_2') }}

                                            @break
                                        @case('type_3')
                                            {{ __('localization.container_view_type_3') }}

                                            @break
                                        @case('type_4')
                                            {{ __('localization.container_view_type_4') }}

                                            @break
                                        @default
                                            {{ $container->type->name }}
                                    @endswitch
                                </p>
                            </x-card.card-data-row>

                            <x-card.card-data-row
                                :label="__('localization.container_view_reversible')"
                            >
                                @if ($container->reversible)
                                    <span class="badge bg-success py-50 px-1">
                                        {{ __('localization.container_view_reversible_on') }}
                                    </span>
                                @else
                                    <span class="badge bg-danger py-50 px-1">
                                        {{ __('localization.container_view_reversible_off') }}
                                    </span>
                                @endif
                            </x-card.card-data-row>
                        </x-card.card-data-wrapper>

                        <x-ui.section-card-title level="5">
                            {{ __('localization.container_view_parameters') }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <x-card.card-data-row
                                :label="__('localization.container_view_weight')"
                                :value="sprintf('%s %s', $container->weight, __('localization.container_view_weight_unit'))"
                            />

                            <x-card.card-data-row
                                :label="__('localization.container_view_max_weight')"
                                :value="sprintf('%s %s', $container->max_weight, __('localization.container_view_weight_unit'))"
                            />

                            <x-card.card-data-row
                                :label="__('localization.container_view_dimensions')"
                                :value="sprintf('%sx%sx%s %s', $container->height, $container->length, $container->width, __('localization.container_view_dimensions_unit'))"
                            />
                        </x-card.card-data-wrapper>
                    </x-ui.col-row-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script type="module">
        import { setupPrintButton } from '{{ asset('assets/js/utils/print-btn.js') }}';

        setupPrintButton('jsPrintBtn', 'jsCopyEventScope');
    </script>

    <script type="module">
        import { setupCopyButton } from '{{ asset('assets/js/utils/copy-btn.js') }}';

        setupCopyButton('jsCopyBtn', 'jsCopyEventScope');
    </script>

    <script type="module">
        import { copyGroup } from '{{ asset('assets/js/utils/copy-group.js') }}';

        copyGroup(); // За замовчуванням

        // або з кастомними селекторами / шаблоном:
        // setupCopyGroup('.my-copy-btn', {
        //     getContentId: (btnId) => btnId.replace('copy-btn-', 'content-'),
        //     onCopied: (button, contentElement, value) => {
        //         console.log(`Скопійовано: ${value}`);
        //     }
        // });
    </script>
@endsection
