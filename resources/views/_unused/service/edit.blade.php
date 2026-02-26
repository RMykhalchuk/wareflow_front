@extends('layouts.admin')
@section('title', __('localization.services_edit_title'))
@section('page-style')
    
@endsection

@section('content')
    <div class="container-fluid px-2">
        <!-- Контейнер з навігацією і кнопками -->
        <div
            class="d-flex justify-content-between flex-column flex-sm-column flex-md-row flex-lg-row flex-xxl-row"
        >
            <div class="pb-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-slash">
                        <li class="breadcrumb-item">
                            <a class="link-secondary" href="/services">
                                {{ __('localization.services_edit_breadcrumb_services') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="/services/{{ $service->id }}" class="link-secondary">
                                {{ __('localization.services_edit_breadcrumb_view_service', ['name' => $service->name]) }}
                            </a>
                        </li>
                        <li class="breadcrumb-item fw-bolder active" aria-current="page">
                            {{ __('localization.services_edit_breadcrumb_edit_service', ['name' => $service->name]) }}
                        </li>
                    </ol>
                </nav>
            </div>

            <div>
                <button
                    type="button"
                    class="btn border-0"
                    tabindex="4"
                    id="edit-save-as-draft-button"
                >
                    <span class="align-middle d-sm-inline-block text-primary">
                        {{ __('localization.services_edit_save_as_draft') }}
                    </span>
                </button>

                <button type="button" class="btn btn-primary" tabindex="4" id="edit-save-button">
                    <span class="align-middle d-sm-inline-block">
                        {{ __('localization.services_edit_save') }}
                    </span>
                </button>
            </div>
        </div>

        <!-- Контейнер  з селектами та полями  для створення послуги-->
        <div class="card p-2 pb-4">
            <h4 class="mb-2 fw-bolder">{{ __('localization.services_edit_basic_data') }}</h4>

            <!-- Набір селектів та інпутів  -->
            <div class="row pb-4">
                <div class="col-12 col-sm-6 input-with-switch mb-1">
                    <div class="w-100 mr-1">
                        <label class="form-label" for="basicInput">
                            {{ __('localization.services_edit_label_name') }}
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="basicInput"
                            placeholder="{{ __('localization.services_edit_placeholder_name') }}"
                            value="{{ $service->name }}"
                        />
                    </div>
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="select-service-category">
                        {{ __('localization.services_edit_label_category') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="select-service-category"
                        data-id="{{ $service->category_id }}"
                        data-dictionary="service_category"
                        data-placeholder="{{ __('localization.services_edit_placeholder_category') }}"
                    >
                        <option value=""></option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <textarea
                        class="form-control"
                        id="exampleFormControlTextarea1"
                        rows="3"
                        placeholder="{{ __('localization.services_edit_placeholder_comment') }}"
                    >
{{ $service->comment }}</textarea
                    >
                </div>

                <div class="col-12">
                    <div id="alert-error" class="alert alert-danger d-none" role="alert">
                        {{ __('localization.services_edit_error_alert') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        const service = {!! json_encode($service) !!};
    </script>

    <script type="module" src="{{ asset('assets/js/entity/service/service.js') }}"></script>
@endsection
