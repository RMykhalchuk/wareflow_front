@extends('layouts.admin')
@section('title', __('localization.workspaces_setting_title'))

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
                    class="col-12 col-sm-12 col-md-12 col-lg-9 col-xxl-9 px-0"
                    style="max-width: 798px"
                >
                    <div class="tab-content card pb-0">
                        <div
                            class="tab-pane active"
                            id="vertical-pill-4"
                            role="tabpanel"
                            aria-labelledby="stacked-pill-4"
                        >
                            <div class="workspace-tab">
                                <div class="d-flex justify-content-between align-items-center p-2">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column justify-content-between">
                                            <h5>{{ $workspace->name }}</h5>
                                            <div>
                                                <span style="padding-right: 5px">
                                                    {{ __('localization.workspaces_settings_tab_employees') }}:
                                                    <span style="font-weight: 500">
                                                        {{ $usersInWorkspaceCount }}
                                                    </span>
                                                </span>
                                                <span>
                                                    {{ __('localization.workspaces_settings_tab_companies') }}:
                                                    <span style="font-weight: 500">
                                                        {{ $companiesCount }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger waves-effect"
                                        >
                                            <span class="text-danger">
                                                {{ __('localization.workspaces_settings_tab_deactivate') }}
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="d-flex justify-content-between px-2 list-document-effect"
                                    id="workspace-details-wrapper"
                                    style="
                                        border-top: 1px solid rgba(75, 70, 92, 0.1);
                                        padding: 18px 0;
                                    "
                                >
                                    <div>
                                        <span style="font-weight: 500">
                                            {{ __('localization.workspaces_settings_tab_details') }}
                                        </span>
                                    </div>
                                    <div>
                                        <i
                                            data-feather="arrow-right"
                                            style="transform: scale(1.5)"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="workspace-details px-2"
                                style="padding-top: 30px; padding-bottom: 30px; display: none"
                            >
                                <div class="workspace-content-wrapper">
                                    <div class="content-header d-flex align-items-center">
                                        <div
                                            class="back-to-workspace-settings"
                                            style="cursor: pointer"
                                        >
                                            <i
                                                data-feather="arrow-left"
                                                class="mx-1"
                                                style="transform: scale(1.5)"
                                            ></i>
                                        </div>
                                        <h4 class="mb-0 ps-1">
                                            {{ __('localization.workspaces_settings_details_title') }}
                                        </h4>
                                    </div>
                                    <div class="row pt-1">
                                        <div class="my-1">
                                            <label class="form-label" for="workspace-username">
                                                {{ __('localization.workspaces_settings_details_name') }}
                                            </label>
                                            <input
                                                type="text"
                                                id="workspace-username"
                                                class="form-control w-100"
                                                maxlength="50"
                                                placeholder="{{ __('localization.workspaces_settings_details_name_placeholder') }}"
                                                value="{{ $workspace->name }}"
                                            />
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="row d-flex justify-content-center">
                                            <div class="d-flex justify-content-end px-2 pb-2">
                                                <button
                                                    type="button"
                                                    class="btn btn-primary"
                                                    id="submit-workspace-detail"
                                                >
                                                    {{ __('localization.workspaces_settings_details_save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script>
        const workspace = {!! json_encode($workspace) !!};
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/workspace/workspace-settings.js') }}"
    ></script>
@endsection
