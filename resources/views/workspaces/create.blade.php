@extends('layouts.empty')

@section('title', __('localization.workspaces_create_title'))

@section('page-style')
    
@endsection

@section('before-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/vendors.min.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/core/menu/menu-types/vertical-menu.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}"
    />
@endsection

@section('content')
    <div class="container-fluid" style="padding: 38px 144px; height: 100vh">
        <section class="vertical-wizard" style="height: 100%">
            <div class="bs-stepper vertical vertical-wizard-example" style="height: 100%">
                <div class="bs-stepper-header" style="background: rgba(217, 180, 20, 0.03)">
                    <div
                        class="step"
                        data-target="#workspace-name"
                        role="tab"
                        id="workspace-name-trigger"
                    >
                        <button type="button" class="step-trigger" style="padding-bottom: 0">
                            <span class="bs-stepper-box">
                                <i data-feather="monitor" style="transform: scale(1.5)"></i>
                            </span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">
                                    {{ __('localization.workspaces_create_wizard_header_workspace_name') }}
                                </span>
                                <span class="bs-stepper-subtitle">
                                    {{ __('localization.workspaces_create_wizard_subheader_workspace_name') }}
                                </span>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="bs-stepper-content">
                    <div
                        class="content h-100"
                        id="workspace-name"
                        role="tabpanel"
                        aria-labelledby="workspace-name-trigger"
                    >
                        <div
                            class="workspace-name-wrapper h-100 d-flex flex-column justify-content-between"
                        >
                            <div class="workspace-content-wrapper">
                                <div class="content-header">
                                    <div
                                        class="content-header-icon-wrapper d-flex align-items-center mb-1"
                                    >
                                        <img
                                            width="25px"
                                            class="content-header-icon"
                                            src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                            alt="{{ config('app.name') }}"
                                            style="margin-right: 5px"
                                        />
                                        <span
                                            class="content-header-icon-text fw-semibold text-center fs-5"
                                        >
                                            {{ config('app.name') }}
                                        </span>
                                    </div>
                                    <h2 class="fw-bolder">
                                        {{ __('localization.workspaces_create_wizard_content_workspace_name_header') }}
                                    </h2>
                                    <h5 class="fw-normal">
                                        {{ __('localization.workspaces_create_wizard_content_workspace_name_subtitle') }}
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="my-1">
                                        <label class="form-label" for="workspace-username">
                                            {{ __('localization.workspaces_create_wizard_content_workspace_name_label') }}
                                        </label>
                                        <input
                                            type="text"
                                            id="workspace-username"
                                            class="form-control"
                                            placeholder="{{ __('localization.workspaces_create_wizard_content_workspace_name_label_placeholder') }}"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button
                                    id="workspace-form-next-btn"
                                    class="btn btn-primary btn-next"
                                >
                                    <span class="align-middle d-sm-inline-block d-none">
                                        {{ __('localization.workspaces_create_wizard_content_workspace_name_next_button') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-wizard.js') }}"></script>
    <script src="{{ asset('js/scripts/components/components-tooltips.js') }}"></script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/workspace/create-workspace.js') }}"
    ></script>
@endsection
