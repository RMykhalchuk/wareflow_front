@extends('layouts.empty')
@section('title', __('localization.workspaces_list_title'))

@section('page-style')
    
@endsection

@section('before-style')
    
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="workspaces-header d-flex flex-column pb-2">
            <h2 class="text-center pb-1 fw-semibold">
                {{ __('localization.workspaces_list_title_header') }}
            </h2>
            <span class="text-center">{!! __('localization.workspaces_list_description') !!}</span>
        </div>

        <div class="row justify-content-start m-0 gy-1" id="">
            <div class="workspace-row-title">
                <h4 class="fw-bolder">{{ __('localization.workspaces_list_my_workspaces') }}</h4>
            </div>

            @foreach ($workspaces as $workspace)
                <div class="col-12 col-sm-6 col-md-6 col-xl-3 col-xxl-3">
                    <div
                        class="list-document-effect h-100 d-flex flex-column justify-content-between p-2"
                        style="border: 1px solid rgba(75, 70, 92, 0.16); border-radius: 6px"
                    >
                        <div class="dropstart">
                            <a
                                class="text-secondary"
                                href="{{ route('workspaces.edit', ['workspace' => $workspace->id]) }}"
                            >
                                <i
                                    data-feather="edit"
                                    class="font-medium-3 cursor-pointer"
                                    style="position: absolute; right: 10px"
                                ></i>
                            </a>
                        </div>
                        <a
                            id="workspace-change-link-{{ $workspace->id }}"
                            data-id="{{ $workspace->id }}"
                            class="d-flex flex-column flex-grow-1 justify-content-between align-items-center"
                            href="#"
                        >
                            <div>
                                <h4 class="pb-1 fw-bolder">{{ $workspace->name }}</h4>
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <p class="text-dark fw-bold mb-0" style="padding-bottom: 5px">
                                    {{ $workspace->owner->name }}
                                </p>
                                <p class="text-dark fw-bold mb-0">
                                    {{ trans_choice('workspace.companies', $workspace->companies_count, ['value' => $workspace->companies_count]) }}
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="col-12 col-sm-6 col-md-6 col-xl-3 col-xxl-3">
                <a class="text-dark" href="{{ route('workspaces.create-company') }}">
                    <div
                        class="list-document-effect p-2"
                        style="border: 1px dashed rgba(75, 70, 92, 0.16); border-radius: 6px"
                    >
                        <div class="card-body">
                            <div
                                class="d-flex justify-content-center align-items-center flex-column gap-1"
                            >
                                <div class="card-avatar">
                                    <img
                                        src="{{ asset('assets/icons/entity/workspace/plus-lg-workspace.svg') }}"
                                    />
                                </div>
                                <div>
                                    <h5 class="card-text fw-bolder text-center">
                                        {{ __('localization.workspaces_list_add_workspace') }}
                                    </h5>

                                    <h5 class="card-text text-white fw-bolder text-center">-</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script
        type="module"
        src="{{ asset('assets/js/entity/workspace/workspace-settings.js') }}"
    ></script>
@endsection
