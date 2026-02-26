@extends('layouts.admin')
@section('title', __('localization.document_types_index_title'))

@section('page-style')
    
@endsection

@section('before-style')
    
@endsection

@section('content')
    <x-layout.container fluid>
        <x-slot:slot>
            <div class="row mx-0" style="column-gap: 144px">
                <div
                    class="col-12 col-sm-12 col-md-3 col-lg-3 col-xxl-3 px-0"
                    style="min-width: 208px; max-width: fit-content"
                >
                    @include('layouts.setting')
                </div>
                <div
                    class="col-12 col-sm-12 col-md-9 col-lg-9 col-xxl-9 px-0"
                    style="max-width: 798px"
                >
                    <div class="tab-content card pb-0">
                        <div
                            class="tab-pane card mb-0 active"
                            id="vertical-pill-2"
                            role="tabpanel"
                            aria-labelledby="stacked-pill-2"
                            aria-expanded="false"
                        >
                            <div class="">
                                <div
                                    class="tab-title d-flex justify-content-between type-card-margin mt-2 mb-1"
                                >
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0 fw-bolder">
                                            {{ __('localization.document_types_index_document_types') }}
                                        </h4>
                                    </div>
                                    <div>
                                        <x-modal.modal-trigger-button
                                            id="add-type-btn"
                                            target="add-type"
                                            class="btn btn-primary"
                                            icon="plus"
                                            iconStyle="mr-0"
                                            text="{{ __('localization.document_types_index_create_new') }}"
                                        />
                                    </div>
                                </div>

                                @if (count($documentTypes) > 0)
                                    <div
                                        class="tab-search type-card-margin mb-2"
                                        style="width: 300px"
                                    >
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text" id="basic-addon-search2">
                                                <i data-feather="search"></i>
                                            </span>
                                            <input
                                                type="text"
                                                class="form-control ps-50"
                                                placeholder="{{ __('localization.document_types_index_search_placeholder') }}"
                                                aria-label="{{ __('localization.document_types_index_search_placeholder') }}"
                                                aria-describedby="basic-addon-search2"
                                                id="searchTypeDoc"
                                            />
                                        </div>
                                    </div>

                                    <div class="document-type-list pt-1">
                                        <div class="card-body px-0 py-0">
                                            <ul
                                                id="typeList"
                                                class="list-group"
                                                style="max-height: 407px; overflow-y: auto"
                                            >
                                                @foreach ($documentTypes as $documentType)
                                                    @php
                                                        if ($documentType->status_id) {
                                                            $color = '';
                                                            if ($documentType->status->key == 'archieve') {
                                                                $color = 'badge-light-dark';
                                                            } elseif ($documentType->status->key == 'system') {
                                                                $color = 'badge-light-primary';
                                                            } elseif ($documentType->status->key == 'draft') {
                                                                $color = 'badge-light-danger';
                                                            }
                                                        }
                                                    @endphp

                                                    <li
                                                        class="list-group-item justify-content-between type-card-margin"
                                                        style="line-height: 2.5em; position: static"
                                                    >
                                                        <p class="m-0 align-self-center">
                                                            <a
                                                                href="#"
                                                                class="list-group-item-action me-2"
                                                            >
                                                                {{ $documentType->name }}
                                                            </a>
                                                            @if ($documentType->status_id)
                                                                <span class="badge {{ $color }}">
                                                                    {{
                                                                        $documentType->status->key == 'archieve'
                                                                            ? __('localization.document_types_index_status_archieve')
                                                                            : ($documentType->status->key == 'system'
                                                                                ? __('localization.document_types_index_status_system')
                                                                                : __('localization.document_types_index_status_draft'))
                                                                    }}
                                                                </span>
                                                            @endif
                                                        </p>
                                                        {{-- @if($isAdmin || $documentType->status_id === null) --}}
                                                        <div class="d-inline-flex">
                                                            <a
                                                                class="pe-1 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    width="24"
                                                                    height="24"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    class="feather feather-more-vertical font-small-4"
                                                                >
                                                                    <circle
                                                                        cx="12"
                                                                        cy="12"
                                                                        r="1"
                                                                    ></circle>
                                                                    <circle
                                                                        cx="12"
                                                                        cy="5"
                                                                        r="1"
                                                                    ></circle>
                                                                    <circle
                                                                        cx="12"
                                                                        cy="19"
                                                                        r="1"
                                                                    ></circle>
                                                                </svg>
                                                            </a>
                                                            <div
                                                                class="dropdown-menu dropdown-menu-end"
                                                                id="dropdown-menu-type"
                                                            >
                                                                <a
                                                                    data-id=""
                                                                    class="dropdown-item edit_package_button"
                                                                    href="{{
                                                                        route('document-type.edit', [
                                                                            'document_type' => $documentType->id,
                                                                            'document_kind' => $documentType->settings()['document_kind'] ?? null,
                                                                        ])
                                                                    }}"
                                                                >
                                                                    {{ __('localization.document_types_index_edit') }}
                                                                </a>

                                                                @if ($documentType->status_id === null || $documentType->status_id === 3)
                                                                    <form
                                                                        method="post"
                                                                        action="{{ route('document-type.status.change', ['status' => 'archieve', 'document_type' => $documentType->id]) }}"
                                                                    >
                                                                        @csrf
                                                                        <button
                                                                            type="submit"
                                                                            class="dropdown-item w-100"
                                                                        >
                                                                            {{ __('localization.document_types_index_archive') }}
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <form
                                                                        method="post"
                                                                        action="{{ route('document-type.status.change', ['status' => 'null', 'document_type' => $documentType->id]) }}"
                                                                    >
                                                                        @csrf
                                                                        <button
                                                                            type="submit"
                                                                            class="dropdown-item w-100"
                                                                        >
                                                                            {{ __('localization.document_types_index_unarchive') }}
                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                <form
                                                                    method="post"
                                                                    action="{{ route('document-type.destroy', ['document_type' => $documentType->id]) }}"
                                                                >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button
                                                                        type="submit"
                                                                        class="dropdown-item w-100"
                                                                    >
                                                                        {{ __('localization.document_types_index_delete') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        {{-- @endif --}}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    @include('layouts.doc-type.add-type-modal')
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/entity/document-type/type-list.js') }}"></script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script type="module">
        import { translateDocTypesName } from '{{ asset('assets/js/localization/document-type/translateDocTypesName.js') }}';

        translateDocTypesName('.list-group-item-action');
    </script>
@endsection
