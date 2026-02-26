@php
    use App\Models\User;
@endphp

@extends('layouts.admin')

@section('title', __('localization.documents.list.title'))

@section('page-style')
    
@endsection

@section('before-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}" />
@endsection

@section('content')
    <div class="card mx-2 bg-transparent box-shadow-0">
        <div class="card-header mx-0 row px-0" style="row-gap: 1rem">
            <div class="col-12 col-md-12 col-lg-8">
                <h1 class="fw-semibold text-dark">
                    {{ __('localization.documents.list.documents') }}
                </h1>
            </div>

            @if ($documentTypes->isNotEmpty())
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" id="basic-addon-search2">
                            <i data-feather="search"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control ps-1"
                            id="searchListDocument"
                            placeholder="{{ __('localization.documents.list.search_placeholder') }}"
                            aria-label="{{ __('localization.documents.list.search_placeholder') }}"
                            aria-describedby="basic-addon-search2"
                        />
                    </div>
                </div>
            @endif
        </div>

        <div class="card-datatable mb-2">
            <div class="row justify-content-start m-0" id="listDocument">
                @if ($documentTypes->isEmpty())
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i
                                data-feather="folder"
                                class="mb-1"
                                style="width: 48px; height: 48px"
                            ></i>
                            <h4 class="fw-bold text-dark mt-2">
                                {{ __('localization.documents.list.no_document_types') }}
                            </h4>
                            <p class="text-muted">
                                {{ __('localization.documents.list.no_document_types_description') }}
                            </p>
                        </div>
                    </div>
                @else
                    @php
                        /** @var User $user */
                        $user = \Auth::user()->load(['workingData.warehouses', 'workingData.currentWarehouse']);
                        $wd = $user->workingData;
                        $warehouses = $wd?->warehouses ?? collect();
                        $disabled = $warehouses->isEmpty();
                        $currentWarehouseId = $wd?->current_warehouse_id;
                        $warehouseId = $currentWarehouseId ?? request()->query('warehouse_id');
                    @endphp

                    @foreach ($documentTypes as $documentType)
                        @php
                            $routeParams = ['document_type' => $documentType];

                            if ($warehouseId) {
                                $routeParams['warehouse_id'] = $warehouseId;
                            }

                            $tableUrl = route('document.table', $routeParams);
                            $isWarehouseSelected = ! $disabled && ! empty($warehouseId);
                        @endphp

                        @if ($documentType->status_id !== 1 && $documentType->status_id !== 3)
                            <div class="col-12 col-sm-6 col-md-6 col-xl-3 col-xxl-3">
                                <div
                                    class="position-relative document-card {{ $isWarehouseSelected ? '' : 'opacity-50 pe-none' }}"
                                    data-warehouse-id="{{ $warehouseId }}"
                                >
                                    <a
                                        class="text-dark goToDocumentsLink"
                                        href="{{ $isWarehouseSelected ? $tableUrl : '#' }}"
                                        data-base-url="{{ route('document.table', ['document_type' => $documentType]) }}"
                                    >
                                        <div class="card list-document-effect">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex justify-content-between flex-column"
                                                    style="height: 94px"
                                                >
                                                    <div
                                                        class="d-flex justify-content-between"
                                                        style="gap: 25px"
                                                    >
                                                        <h5
                                                            class="card-title text-dark mb-0 fw-bolder"
                                                            style="
                                                                line-height: 24px;
                                                                max-width: 200px;
                                                            "
                                                        >
                                                            {{ $documentType->name }}
                                                        </h5>
                                                    </div>
                                                    <p class="card-text">
                                                        {{ __('localization.documents.list.in_progress') }}:
                                                        <b>{{ $documentType->documents_count }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                    <div
                                        class="position-absolute gap-25 d-flex drop-area {{ $isWarehouseSelected ? '' : 'opacity-50' }}"
                                        style="top:20px; right:20px; {{ $isWarehouseSelected ? '' : 'pointer-events: none;' }}"
                                    >
                                        @if ($documentType->documents_count > 0)
                                            <div class="badge bg-light-success d-flex">
                                                <span class="text-dark">
                                                    +{{ $documentType->documents_count }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="dropstart d-flex align-items-center">
                                            <i
                                                data-feather="more-vertical"
                                                class="font-medium-3 cursor-pointer dropdown-toggle"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                            ></i>
                                            <ul class="dropdown-menu" style="width: 189px">
                                                @if ($isAdmin || $documentType->status_id === null)
                                                    <a
                                                        href="{{
                                                            route('document-type.edit', [
                                                                'document_type' => $documentType->id,
                                                                'document_kind' => $documentType->settings()['document_kind'] ?? null,
                                                            ])
                                                        }}"
                                                        class="dropdown-item w-100 doctype-menu-item"
                                                        style="padding-left: 24px"
                                                    >
                                                        {{ __('localization.documents.list.edit') }}
                                                    </a>
                                                @endif

                                                <a
                                                    href="{{ route('document.table', ['document_type' => $documentType, 'warehouse_id' => $warehouseId]) }}"
                                                    class="dropdown-item w-100 doctype-menu-item dynamic-warehouse-link"
                                                    style="padding-left: 24px"
                                                >
                                                    {!! __('localization.documents.list.view_documents') !!}
                                                </a>
                                                <a
                                                    href="{{ route('document.create', ['document_type' => $documentType->id, 'warehouse_id' => $warehouseId]) }}"
                                                    class="dropdown-item w-100 doctype-menu-item dynamic-warehouse-link"
                                                    style="padding-left: 24px"
                                                >
                                                    {!! __('localization.documents.list.create_document') !!}
                                                </a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @include('layouts.doc-type.add-type-modal')
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('assets/js/entity/document/document-list.js') }}"></script>

    <script type="module">
        import { translateDocTypesName } from '{{ asset('assets/js/localization/document-type/translateDocTypesName.js') }}';

        translateDocTypesName('.card-title');
    </script>
@endsection
