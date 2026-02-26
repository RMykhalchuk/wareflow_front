@extends('layouts.admin')
@section('title', __('localization.regulation_index_title'))

@section('page-style')
    
@endsection

@section('before-style')
    
@endsection

@section('content')
    <div class="container-fluid px-3 css-entity-regulation">
        <div class="row" style="column-gap: 144px">
            <div
                class="col-12 col-sm-12 col-md-3 col-lg-3 col-xxl-3 px-0"
                style="min-width: 208px; max-width: fit-content"
            >
                @include('layouts.setting')
            </div>
            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xxl-9 px-0" style="max-width: 798px">
                <div class="tab-content card pb-0">
                    <div
                        role="tabpanel"
                        class="tab-pane mb-0 active"
                        id="vertical-pill-5"
                        aria-labelledby="stacked-pill-5"
                        aria-expanded="true"
                    >
                        <div id="all-regulation">
                            <div class="p-2">
                                <h4 class="fw-bolder mb-0">
                                    {{ __('localization.regulation_index_title_card') }}
                                </h4>
                            </div>
                            <hr class="my-0" />
                            <div id="list-regulation"></div>
                        </div>

                        <div id="selected-type"></div>

                        <div id="create-regulation"></div>

                        <div id="edit-regulation"></div>

                        <div id="view-regulation"></div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade text-start"
            id="archive_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder">
                            <span>
                                {{ __('localization.regulation_index_modal_archive_title') }}
                            </span>
                            <span id="titleModalArchive"></span>
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.regulation_index_modal_archive_confirmation') }}
                            </p>
                            <div class="col-12">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn link-primary text-secondary me-1"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        {{ __('localization.regulation_index_modal_archive_cancel_button') }}
                                    </button>
                                    <button type="button" class="btn btn-primary" id="archiveRule">
                                        {{ __('localization.regulation_index_modal_archive_confirm_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade text-start"
            id="delete_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder" id="titleModalRegulationDelete">
                            {{ __('localization.regulation_index_modal_delete_title') }}
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.regulation_index_modal_delete_confirmation') }}
                            </p>
                            <div class="col-12">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn link-primary text-secondary me-1"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        {{ __('localization.regulation_index_modal_delete_cancel_button') }}
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        id="deleteRegulation"
                                    >
                                        {{ __('localization.regulation_index_modal_delete_confirm_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade text-start"
            id="no_delete_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder" id="titleModalNoRegulationDelete">
                            {{ __('localization.regulation_index_modal_no_delete_title') }}
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.regulation_index_modal_no_delete_message_part1') }}
                                <a class="link-primary" href="#">
                                    {{ __('localization.regulation_index_modal_no_delete_link') }}
                                </a>
                                .
                                <br />
                                {{ __('localization.regulation_index_modal_no_delete_message_part2') }}
                            </p>

                            <div class="col-12">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        {{ __('localization.regulation_index_modal_no_delete_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade text-start"
            id="no_archive_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder" id="titleModalNoArchive">
                            {{ __('localization.regulation_index_modal_no_archive_title') }}
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.regulation_index_modal_no_archive_message_part1') }}
                                <a class="link-primary" href="#">
                                    {{ __('localization.regulation_index_modal_no_archive_link') }}
                                </a>
                                .
                                <br />
                                {{ __('localization.regulation_index_modal_no_archive_message_part2') }}
                            </p>

                            <div class="col-12">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        {{ __('localization.regulation_index_modal_no_archive_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade text-start"
            id="save_edit_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder" id="titleModalSaveEditRegulation">
                            {{ __('localization.regulation_index_modal_save_edit_title') }}
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.regulation_index_modal_save_edit_message_part1') }}
                                <br />
                                {!! __('localization.regulation_index_modal_save_edit_message_part2') !!}
                            </p>

                            <div class="form-check form-check-warning">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="save-children-regulation"
                                />
                                <label class="form-check-label" for="save-children-regulation">
                                    {{ __('localization.regulation_index_modal_save_edit_checkbox') }}
                                </label>
                            </div>

                            <div class="col-12 mt-1">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn link-primary text-secondary me-1"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        {{ __('localization.regulation_index_modal_save_edit_cancel') }}
                                    </button>
                                    <button
                                        type="button"
                                        id="save-edit-regulation"
                                        class="btn btn-primary"
                                        data-dismiss="modal"
                                    >
                                        {{ __('localization.regulation_index_modal_save_edit_confirm') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="module" src="{{ asset('assets/js/entity/regulation/regulation.js') }}"></script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
@endsection
