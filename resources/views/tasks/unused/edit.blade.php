@extends('layouts.admin')
@section('title', __('localization.tasks.create.title'))

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

@section('page-style')
    
@endsection

@section('table-js')
    @include('layouts.table-scripts')
    <script type="module" src="{{ asset('assets/js/grid/tasks/view-details-table.js') }}"></script>
@endsection

@section('content')
    @php
        $tasksType = request()->get('type', 'internal_displacement'); // за замовчуванням full
    @endphp

    <x-layout.container id="tasks-container" fluid data-type="{{ $tasksType }}">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/tasks',
                            'name' => __('localization.tasks.create.title'),
                        ],
                        [
                            'name' => __('localization.tasks.create.create_tasks'),
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
            {{-- internal_displacement tasks --}}
            <div
                id="full_tasks"
                style="display: {{ $tasksType === 'internal_displacement' ? 'block' : 'none' }}"
            >
                <div class="d-flex gap-1 align-items-center">
                    <x-page-title
                        :title="__('localization.tasks.create.titles.internal_displacement')"
                    />
                </div>
                <x-card.nested>
                    <x-slot:header>
                        <x-section-title>
                            {{ __('localization.tasks.create.main_information') }}
                        </x-section-title>
                    </x-slot>

                    <x-slot:body>
                        <x-form.select
                            id="warehouse_full"
                            name="warehouse_full"
                            label="localization.tasks.create.warehouse"
                            placeholder="localization.tasks.create.select_warehouse"
                            data-dictionary="warehouses"
                        />

                        <x-form.select
                            id="performer_type_full"
                            name="performer_type_full"
                            label="localization.tasks.create.performer"
                            placeholder="localization.tasks.create.choose_tasks_performers"
                            data-dictionary="users"
                            :data-custom-options='json_encode([["id" => "all", "text" => __("localization.inventory.create.all")]])'
                            multiple
                        />

                        <x-form.select
                            id="type_full"
                            name="type_full"
                            label="localization.tasks.create.type"
                            placeholder="localization.tasks.create.select_type"
                            data-dictionary="type"
                        />

                        <x-form.select
                            id="cell_2"
                            name="cell_2"
                            label="Місце до розміщення*"
                            placeholder="Місце розміщення"
                            data-dictionary="cells"
                        />

                        <x-form.input-group-wrapper wrapperClass="col-12 px-0">
                            <p class="mb-50">
                                {{ __('localization.tasks.create.priority') }}
                            </p>

                            <div class="d-flex gap-1">
                                @for ($i = 1; $i <= 10; $i++)
                                    <div class="m-0">
                                        <input
                                            type="radio"
                                            class="btn-check"
                                            name="priority_full"
                                            id="priority_{{ $i }}_full"
                                            autocomplete="off"
                                            @if ($i === $task->priority) checked @endif
                                        />
                                        <label
                                            class="btn py-75 px-1 btn-outline-primary d-flex align-items-center gap-1"
                                            for="priority_{{ $i }}_full"
                                        >
                                            <input
                                                type="radio"
                                                class="form-check-input"
                                                hidden
                                                @if ($i === $task->priority) checked @endif
                                            />
                                            <span>{{ $i }}</span>
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </x-form.input-group-wrapper>

                        <x-form.textarea
                            id="comment_full"
                            name="comment_full"
                            label=""
                            placeholder="localization.tasks.create.comment"
                        />

                        <div class="mt-1" id="main-data-message_full"></div>
                    </x-slot>
                </x-card.nested>

                <x-card.nested>
                    <x-slot:header>
                        <div class="d-flex flex-grow-1 justify-content-between">
                            <x-section-title>
                                {{ __('localization.tasks.create.leftovers') }}
                            </x-section-title>

                            <x-form.select
                                id="cell"
                                name="cell"
                                label="Локація відбору:"
                                placeholder="Виберіть локацію"
                                data-dictionary="cells"
                                class="d-flex gap-1 align-items-center"
                            />
                        </div>
                    </x-slot>

                    <x-slot:body>
                        <div
                            class="px-1 mt-1 bg-secondary-subtle d-flex justify-content-center rounded align-items-center text-dark height-100 p-1"
                        >
                            <x-modal.modal-trigger-button
                                id="add_leftovers_button"
                                target="add_leftovers"
                                class="btn btn-outline-secondary d-none"
                                :text="__('localization.tasks.create.add_leftovers')"
                                icon="plus"
                            />

                            <div id="placeholder_lefovers" class="d-block">
                                <span class="fw-normal">
                                    Виберіть локацію відбору для додавання залишку
                                </span>
                            </div>
                        </div>

                        <div class="mt-1" id="sku-data-message_full"></div>
                    </x-slot>
                </x-card.nested>
            </div>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="create"
                    class="btn btn-primary mb-2"
                    :text="__('localization.tasks.create.save')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <x-modal.base id="add_leftovers" size="modal-lg" style="max-width: 1800px !important">
        <x-slot name="header">
            <div class="d-flex flex-grow-1 justify-content-between align-items-center">
                <div class="d-flex flex-column gap-50">
                    <div class="fw-bold">{{ __('localization.tasks.create.leftovers') }}</div>
                    <p class="mb-0 fw-bolder fs-4">A11-02-93</p>
                </div>

                <div class="d-flex align-items-center gap-25">
                    <button type="button" class="btn btn-primary" id="package_submit">
                        Підтвердити
                    </button>
                    <div>|</div>
                    <button
                        class="btn btn-flat-secondary p-1"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i data-feather="x"></i>
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="card-grid" style="position: relative">
                @include('layouts.table-setting')
                <div class="table-block" id="view-details-table"></div>
            </div>
        </x-slot>
    </x-modal.base>

    <x-cancel-modal
        id="cancel_button_modal"
        route="/tasks"
        title="{{ __('localization.tasks.cancel.create.modal.title') }}"
        content="{!! __('localization.tasks.cancel.create.modal.content') !!}"
        cancel-text="{{ __('localization.tasks.cancel.cancel_button') }}"
        confirm-text="{{ __('localization.tasks.cancel.confirm_button') }}"
    />
@endsection

@section('page-script')
    <script>
        let cellId = '1';
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

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

    <script type="module" src="{{ asset('assets/js/entity/tasks/tasks.js') }}"></script>

    <script type="module">
        import { initSelectWithValidationToggle } from '{{ asset('assets/js/utils/selectToggle.js') }}';

        initSelectWithValidationToggle('cell', 'placeholder_lefovers', 'add_leftovers_button');
    </script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#view-details-table'), '', 200, 250);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#view-details-table'));
    </script>
@endsection
