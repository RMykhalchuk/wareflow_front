@extends('layouts.admin')
@section('title', __('localization.tasks.index.title'))

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

@section('table-js')
    @include('layouts.table-scripts')
    <script type="module" src="{{ asset('assets/js/grid/tasks/tasks-table.js') }}"></script>
@endsection

@section('content')
    @php
        /** @var User $user */
        $user = \Auth::user()->load(['workingData.warehouses', 'workingData.currentWarehouse']);

        $wd = $user->workingDataByGuard;
        $warehouses = $wd?->warehouses ?? collect();
        $disabled = $warehouses->isEmpty();
        // Встановлюємо ID складу, який буде використовуватися для фільтрації при завантаженні
        $warehouseId = $wd?->current_warehouse_id;

        // Передаємо лише базовий URL без фільтрації для ініціалізації сітки
        $language = \App::getLocale() === 'en' ? '' : '/' . \App::getLocale();
        $baseUrl = url($language . '/tasks/table/filter');

        $isTestMode = true; // або false, щоб приховати кнопку
    @endphp

    @if ($tasksCount)
        @if (! $isTestMode)
            <x-layout.index-table-card
                :title="__('localization.tasks.index.title_header')"
                :buttonText="__('localization.tasks.index.add_button')"
                buttonModalToggle="modal"
                buttonModalTarget="#select-type"
                tableId="tasks-table"
            />
        @else
            <x-layout.index-table-card
                :title="__('localization.tasks.index.title_header')"
                tableId="tasks-table"
            />
        @endif
    @else
        @if (! $isTestMode)
            <x-layout.index-empty-message
                :title="__('localization.tasks.index.add_prompt')"
                :message="__('localization.tasks.index.empty_message')"
                :buttonText="__('localization.tasks.index.add_button_text')"
                buttonModalToggle="modal"
                buttonModalTarget="#select-type"
            />
        @else
            <x-layout.index-empty-message
                :title="__('localization.tasks.index.add_prompt')"
                :message="__('localization.tasks.index.empty_message')"
            />
        @endif
    @endif

    @if (! $isTestMode)
        <x-modal.base id="select-type" size="modal-lg" style="max-width: 680px !important">
            <x-slot name="header">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <x-ui.section-card-title level="2" class="modal-title">
                        {{ __('localization.tasks.modal.title') }}
                    </x-ui.section-card-title>
                    <p class="text-muted">
                        {{ __('localization.tasks.modal.desc') }}
                    </p>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row g-2 mx-0 mb-4">
                    <div class="col-6">
                        <a
                            href="{{ route('tasks.create', ['type' => 'internal_displacement']) }}"
                            class="card h-100 border shadow-sm text-decoration-none"
                        >
                            <div class="card-body text-center">
                                <h5 class="fw-bold mb-2">
                                    {{ __('localization.tasks.modal.full_title') }}
                                </h5>
                                <p class="mb-0 text-muted">
                                    {{ __('localization.tasks.modal.full_description') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a
                            disabled
                            href="#"
                            class="card h-100 border shadow-sm opacity-75 bg-light-secondary text-decoration-none"
                        >
                            <div class="card-body text-center">
                                <h5 class="fw-bold mb-2">
                                    {{ __('localization.tasks.modal.simple_title') }}
                                </h5>
                                <p class="mb-0 text-muted">
                                    {{ __('localization.tasks.modal.simple_description') }}
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </x-slot>
        </x-modal.base>
    @endif
@endsection

@section('page-script')
    <script>
        window.baseUrlTasks = '{{ $baseUrl }}';
    </script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#tasks-table'), '', 100, 150);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#tasks-table'));
    </script>
@endsection
