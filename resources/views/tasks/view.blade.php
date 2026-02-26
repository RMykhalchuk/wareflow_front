@extends('layouts.admin')
@section('title', __('localization.tasks.view.title'))

@section('page-style')
    
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
    $status = $task->status_info['value']; // Можливі значення: new, in_progress, paused, finished, in_progress_an_animal
    $tasksKind = 'Picking'; // Possible values: 'Receiving', 'Picking', 'Internal transfer'
@endphp

@section('table-js')
    @include('layouts.table-scripts')
    @if ($tasksKind === 'Picking' || $tasksKind === 'Receiving')
        <script type="module" src="{{ asset('assets/js/grid/tasks/sku-table.js') }}"></script>
        <script
            type="module"
            src="{{ asset('assets/js/grid/tasks/leftovers-by-sku-table.js') }}"
        ></script>
    @endif

    @if ($tasksKind === 'Internal transfer')
        <script
            type="module"
            src="{{ asset('assets/js/grid/tasks/leftovers-table.js') }}"
        ></script>
    @endif
@endsection

@section('content')
    <x-layout.container
        id="task-container"
        data-document-id="{{ $task->document->id }}"
        data-id="{{ $task->id }}"
        fluid
    >
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/tasks',
                            'name' => __('localization.tasks.view.breadcrumb.tasks'),
                        ],
                        [
                            'name' => __('localization.tasks.view.breadcrumb.tasks') . ' №',
                            'name2' => $task->local_id ?? '-',
                        ],
                    ],
                ]
            )

            @if ($status === 1)
                <x-ui.header-actions>
                    <x-ui.icon-dropdown
                        id="header-dropdown"
                        menuClass="d-flex flex-column justify-content-center px-1"
                    >
                        {{-- <x-ui.icon-link-button --}}
                        {{-- href="{{ route('tasks.edit', ['task' => '1']) }}" --}}
                        {{-- :title="__('localization.tasks.view.edit_icon_tooltip')" --}}
                        {{-- icon="edit" --}}
                        {{-- /> --}}
                        {{-- <x-modal.modal-trigger-button --}}
                        {{-- id="cancel_button" --}}
                        {{-- target="deleteModalCenter" --}}
                        {{-- class="btn btn-flat-danger" --}}
                        {{-- :text="__('localization.tasks.view.modal_delete_confirm')" --}}
                        {{-- /> --}}
                        <x-ui.dropdown-item
                            href="#"
                            id="cancelTask"
                            :text="__('localization.tasks.view.actions.cancel_task')"
                        />
                    </x-ui.icon-dropdown>
                </x-ui.header-actions>
            @endif
        </x-slot>

        <x-slot:slot>
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <div class="d-flex gap-1 align-items-center">
                    <h1 class="m-0">
                        {{ __('localization.tasks.view.breadcrumb.tasks') }}
                        №{{ $task->local_id }}
                    </h1>

                    @php
                        $badgeClass = match ($task->status_info['value']) {
                            0 => 'bg-light-secondary',
                            1 => 'bg-light-success',
                            2 => 'bg-light-info',
                            3 => 'bg-light-danger',
                            default => 'bg-dark',
                        };
                    @endphp

                    <span class="badge {{ $badgeClass }}">
                        {{ $task->status->label() }}
                    </span>
                </div>

                @if ($status === 1)
                    <button id="send_to_work" class="btn btn-success">
                        <span class="text-white">
                            {{ __('localization.tasks.view.send_to_work') }}
                        </span>
                    </button>
                @endif

                @if ($status === 033)
                    <div>
                        <button class="btn btn-outline-secondary">
                            <i data-feather="pause"></i>
                        </button>
                        <button class="btn btn-outline-secondary">
                            {{ __('localization.tasks.view.finish_early') }}
                        </button>
                    </div>
                @endif

                @if ($status === 044)
                    <button class="btn btn-success">
                        <i data-feather="check"></i>
                        {{ __('localization.tasks.view.finish_tasks') }}
                    </button>
                @endif
            </div>

            <x-ui.info-card :title="__('localization.tasks.view.main_info')">
                <div class="row mx-0 p-0 gy-1">
                    <div class="col-6 d-flex">
                        <span class="me-2 col-6">
                            {{ __('localization.tasks.view.warehouse') }}
                        </span>
                        <span class="col-6 fw-bold">
                            {{ $task->cell->allocation['warehouse'] }}
                        </span>
                    </div>

                    @if ($tasksKind === 'Receiving')
                        <div class="col-6 d-flex">
                            <span class="me-2 col-6">
                                {{ __('localization.tasks.view.place_to') }}
                            </span>
                            <span class="col-6 fw-bold">{{ $task->cell->code }}</span>
                        </div>
                    @endif

                    @if ($tasksKind === 'Picking')
                        <div class="col-6 d-flex">
                            <span class="me-2 col-6">
                                {{ __('localization.tasks.view.place_placement') }}
                            </span>
                            <span class="col-6 fw-bold">{{ $task->cell->code }}</span>
                        </div>
                    @endif

                    @if ($tasksKind === 'Internal transfer')
                        <div class="col-6 d-flex">
                            <span class="me-2 col-6">
                                {{ __('localization.tasks.view.place_from') }}
                            </span>
                            <span class="col-6 fw-bold">{{ $task->cell->code }}</span>
                        </div>
                    @endif

                    <div class="col-6">
                        <div class="d-flex align-items-start">
                            <div class="me-2 col-6">
                                {{ __('localization.tasks.view.executors') }}
                            </div>
                            <div class="col-6 gap-50 d-flex align-items-center flex-wrap">
                                @if ($task->executorUsers && count($task->executorUsers))
                                    @foreach ($task->executorUsers as $user)
                                        <div
                                            class="border rounded py-50 px-1 d-flex align-items-center justify-content-center gap-25 executor-item"
                                            data-executor-id="{{ $user->id }}"
                                        >
                                            <img
                                                src="https://ui-avatars.com/api/?name={{ $user->surname . ' ' . mb_substr($user->name, 0, 1) . '.' . mb_substr($user->patronymic, 0, 1) }}"
                                                class="rounded-circle"
                                                width="24"
                                            />
                                            <span>
                                                {{ $user->surname . ' ' . mb_substr($user->name, 0, 1) . '.' . mb_substr($user->patronymic, 0, 1) }}
                                            </span>
                                            @if ($status !== 3)
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-link text-dark p-0 remove-executor"
                                                    title="{{ __('localization.tasks.view.remove_executor') }}"
                                                >
                                                    <i data-feather="x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <span class="border rounded py-50 px-1">
                                        {{ __('localization.tasks.view.all') }}
                                    </span>
                                @endif

                                @if ($status !== 3)
                                    <x-modal.modal-trigger-button
                                        id="executorUsersBtn"
                                        target="addExecutorUsers"
                                        class="btn btn-flat-dark py-75 px-1"
                                        :text="__('localization.tasks.view.add_executor')"
                                        :icon="'plus'"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($tasksKind === 'Internal transfer')
                        <div class="col-6 d-flex">
                            <span class="me-2 col-6">
                                {{ __('localization.tasks.view.place_placement') }}
                            </span>
                            <span class="col-6 fw-bold">{{ $task->cell->code }}</span>
                        </div>
                    @endif

                    <div class="col-6 d-flex">
                        <span class="me-2 col-6">{{ __('localization.tasks.view.type') }}</span>
                        <span class="col-6 d-flex align-items-start">
                            <span class="fw-bolder">
                                {{ $task->type->getTranslation('name', app()->getLocale()) }}
                            </span>
                        </span>
                    </div>

                    <div class="col-6 d-flex align-items-center">
                        <span class="me-2 col-6">
                            {{ __('localization.tasks.view.priority') }}
                        </span>
                        <div
                            id="priority_full_dropdown"
                            class="priority-container col-6 flex-wrap gap-25"
                        >
                            @foreach (range(0, 10) as $i)
                                <button
                                    type="button"
                                    @if($status === 3) disabled @endif
                                    class="priority-btn {{ isset($task) && $i === $task->priority ? 'active show' : '' }}"
                                    data-value="{{ $i }}"
                                >
                                    {{ $i }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-ui.info-card>

            @if ($tasksKind === 'Receiving' || $tasksKind === 'Picking')
                <x-layout.index-table-card
                    :title="__('localization.tasks.view.nomenclature')"
                    tableId="sku-table"
                    class="mx-0"
                />
            @endif

            @if ($tasksKind === 'Internal transfer')
                <x-layout.index-table-card
                    :title="__('localization.tasks.view.leftovers_info')"
                    tableId="leftovers-table"
                    idOne="settingTable-1"
                    idTwo="changeFonts-1"
                    idThree="changeCol-1"
                    idFour="jqxlistbox-1"
                    class="mx-0"
                />
            @endif
        </x-slot>
    </x-layout.container>

    <x-modal.base id="target" size="modal-lg" style="max-width: 1400px !important">
        <x-slot name="header">
            <div class="d-flex flex-grow-1 justify-content-between gap-1 align-items-center">
                <div class="d-flex flex-column gap-50 col-4">
                    <div class="fw-bold">
                        {{ __('localization.inventory.view.modals.leftovers') }}
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <!-- Назва комірки -->
                        <div
                            id="leftovers-name"
                            class="mb-0 fw-bolder fs-4 w-auto"
                            data-goods-id=""
                            data-quantity="0"
                        >
                            {{ __('localization.tasks.view.goods') }} -
                        </div>
                        <div class="d-flex flex-column w-50 align-items-center gap-75">
                            <div class="d-flex align-items-center w-100">
                                <div class="progress w-75 me-50" style="height: 6px">
                                    <div
                                        id="leftovers-progress-bar"
                                        class="progress-bar bg-warning"
                                        role="progressbar"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        style="width: 0%"
                                    ></div>
                                </div>
                                <div class="small w-25">
                                    <span id="leftovers-current">0</span>
                                    /
                                    <span id="leftovers-max">0</span>
                                    <span id="leftovers-unit">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-25">
                    <button
                        class="btn btn-flat-secondary p-1"
                        data-bs-dismiss="modal"
                        aria-label="{{ __('localization.tasks.close') }}"
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
                        'idOne' => 'settingTable-2',
                        'idTwo' => 'changeFonts-2',
                        'idThree' => 'changeCol-2',
                        'idFour' => 'jqxlistbox-2',
                    ]
                )
                <div class="table-block d-none" id="leftovers-by-sku-table"></div>
            </div>

            <!-- Порожній блок -->
            <div
                id="empty-cell-block"
                class="px-1 bg-secondary-100 d-flex flex-grow-1 flex-column gap-1 justify-content-center rounded align-items-center height-200 text-dark p-1 d-none"
            >
                <h3 id="placeholder_leftovers" class="d-block">
                    {{ __('localization.tasks.view.leftovers_empty') }}
                </h3>
            </div>
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-modal.base>

    <!-- Delete modal -->
    <x-modal.delete-modal
        modalId="deleteModalCenter"
        {{-- :action="route('tasks.destroy', ['tasks' => $tasks])" --}}
        :action="route('tasks.destroy', ['task' => '1'])"
        title="localization.tasks.view.modal_delete.title"
        description="localization.tasks.view.modal_delete.confirmation"
        cancelText="localization.tasks.view.modal_delete.cancel"
        confirmText="localization.tasks.view.modal_delete.submit"
    />

    <x-modal.base id="addExecutorUsers" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.tasks.view.add_executor') }}
                </x-ui.section-card-title>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="row mx-0 js-modal-form">
                <x-form.select
                    id="executor_user_id"
                    name="executor_user_id"
                    :label="__('localization.tasks.view.executor_label')"
                    :placeholder="__('localization.tasks.view.executor_placeholder')"
                    data-dictionary="users"
                    class="col-12"
                />

                <div id="message-executors" class="mt-1"></div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button type="button" class="btn btn-link" data-bs-dismiss="modal">
                {{ __('localization.tasks.view.cancel.cancel_button') }}
            </button>
            <button type="button" class="btn btn-primary" id="submit_executors">
                {{ __('localization.tasks.view.add_executor') }}
            </button>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/tasks/tasks.js') }}"></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#sku-table'));
        tableSetting($('#leftovers-by-sku-table'), '-2', 100, 150);
        @if ($tasksKind === 'Internal transfer')
        tableSetting($('#leftovers-table'), '-1', 100, 150);
        @endif
    </script>

    <script>
        // 1️⃣ Початкові дані від PHP
        window.progressData = <?= json_encode($progressData) ?>;
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#sku-table'));
        offCanvasByBorder($('#leftovers-by-sku-table'), '-2');

        @if ($tasksKind === 'Internal transfer')
        offCanvasByBorder($('#leftovers-table'), '-1');
        @endif
    </script>

    <script type="module">
        const table = $('#sku-table');

        window.tableData = @json($document->getSkuTableInfo());
    </script>

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
@endsection
