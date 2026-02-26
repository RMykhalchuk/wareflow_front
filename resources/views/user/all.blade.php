@extends('layouts.admin')
@section('title', __('localization.user.index.title_page'))

@section('before-style')
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.base.css') }}"
        type="text/css"
    />
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css') }}"
    />

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/css/intlTelInput.css"
    />
@endsection

@section('table-js')
    @include('layouts.table-scripts')

    <script type="module" src="{{ asset('assets/js/grid/user/all-table.js') }}"></script>
@endsection

@section('content')
    @if (count($usersAll))
        <x-layout.index-table-card
            :title="__('localization.user.index.title_table')"
            :buttonText="__('localization.user.index.btn_add_user')"
            buttonModalToggle="modal"
            buttonModalTarget="#modalAddUser"
            tableId="usersDataTable"
        />

        <div
            class="toast basic-toast position-fixed top-0 end-0 mt-5 me-3 fade show"
            style="background: rgb(255, 255, 255); display: none; z-index: 9999 !important"
        >
            <div class="toast-header pt-2">
                <img
                    src="{{ asset('assets/icons/entity/user/check-msg-user.svg') }}"
                    class="me-1"
                    alt="Toast image"
                    height="18"
                    width="25"
                />
                <strong style="font-weight: 600; font-size: 15px" class="me-auto">
                    {{ __('localization.user.index.toast_success_message') }}
                </strong>
                <button
                    type="button"
                    class="ms-0 btn-close p-0"
                    style="width: 45px"
                    data-bs-dismiss="toast"
                    aria-label="Close"
                ></button>
            </div>
            <div id="alert-body" style="margin-left: 50px; font-size: 14px; margin-top: 5px"></div>
            <div class="mt-1 mb-1 d-flex justify-content-between gap-1 pe-2 ps-5 flex-grow-1">
                <button id="send_email" class="btn send_email py-0 px-1 btn-primary">
                    <img
                        class="me-1"
                        src="{{ asset('assets/icons/entity/user/mail-forward.svg') }}"
                        alt="mail-forward"
                    />
                    {{ __('localization.user.index.btn_send_email') }}
                </button>
                <button type="button" id="copy" class="btn copy px-1 py-0 btn-outline-primary">
                    <i class="me-1" data-feather="copy"></i>
                    {{ __('localization.user.index.btn_copy') }}
                </button>
            </div>
        </div>

        <textarea
            style="position: absolute; left: -999999999px"
            name="temp"
            tabindex="-1"
            id="temp"
        ></textarea>
    @else
        <x-layout.index-empty-message
            :title="__('localization.user.index.empty_list_message')"
            :message="__('localization.user.index.empty_list_submessage')"
            :buttonText="__('localization.user.index.btn_add_user')"
            buttonModalToggle="modal"
            buttonModalTarget="#modalAddUser"
        />
    @endif

    <!-- Add user modal -->
    <x-modal.base id="modalAddUser" size="modal-lg" style="max-width: 555px !important">
        <x-slot name="header">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <x-ui.section-card-title level="3" class="modal-title pb-1">
                    {{ __('localization.user.index.modal_title') }} {{ config('app.name') }}
                </x-ui.section-card-title>
                <p class="text-center px-2 mb-0">
                    {{ __('localization.user.index.modal_subtitle') }}
                </p>
            </div>
        </x-slot>

        <x-slot name="body">
            <form
                id="add-user-form"
                class="w-100 h-100 d-flex flex-column px-0"
                action="/users/create"
                method="GET"
            >
                <div class="mb-2 input-email-group-modal">
                    <label class="form-label" for="addUserEmailInp">
                        {{ __('localization.user.index.label_email') }}
                    </label>
                    <input
                        class="form-control"
                        id="addUserEmailInp"
                        type="email"
                        name="login"
                        placeholder="example@email.com"
                        aria-describedby="addUserEmailInp"
                        autofocus=""
                        tabindex="1"
                        style="margin-bottom: 5px"
                    />
                    <a
                        href="#"
                        class="text-secondary text-decoration-underline link-to-numberInputModal"
                    >
                        {{ __('localization.user.index.link_use_phone_number') }}
                    </a>
                </div>
                <!-- КАСТОМІЗОВАНИЙ ІНПУТ для номеру телефону різних країн -->
                <div
                    class="input-number-group-modal inpSelectNumCountry"
                    style="padding-top: 2px; margin-bottom: 7px"
                >
                    <div class="mb-1 d-flex flex-column">
                        <label class="form-label" for="addUserNumberInp">
                            {{ __('localization.user.index.label_phone') }}
                        </label>
                        <input
                            class="form-control input-number-country"
                            id="feedBackNumberInp2"
                            name="login"
                            aria-describedby="feedBackNumberInp2"
                            autofocus=""
                        />
                        <a
                            href="#"
                            class="text-secondary text-decoration-underline link-to-emailInputModal"
                            style="margin-top: 5px"
                        >
                            {{ __('localization.user.index.link_login_with_email') }}
                        </a>
                    </div>
                </div>

                <div class="">
                    <div
                        class="d-flex gap-1 align-items-center"
                        style="
                            background-color: rgba(217, 180, 20, 0.2);
                            color: rgba(217, 180, 20, 1);
                            padding: 12px 14px;
                            border-radius: 6px;
                        "
                    >
                        <img
                            src="{{ asset('assets/icons/entity/user/alert-circle-user.svg') }}"
                            alt=""
                        />
                        <span>
                            {{ __('localization.user.index.already_registered_message') }}
                        </span>
                    </div>
                </div>
                <!-- end input -->
                <div class="col-12 my-3">
                    <div class="d-flex float-end">
                        <button
                            type="button"
                            class="btn btn-link cancel-btn"
                            data-bs-dismiss="modal"
                        >
                            {{ __('localization.user.index.btn_cancel') }}
                        </button>
                        <button
                            id="nextStepAuth"
                            class="btn btn-primary float-end"
                            type="submit"
                            disabled
                        >
                            <span class="me-50">
                                {{ __('localization.user.index.btn_add') }}
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/js/intlTelInput.min.js"></script>

    <script type="module">
        import { inputSelectCountry } from '{{ asset('assets/js/utils/inputSelectCountry.js') }}';

        inputSelectCountry('#feedBackNumberInp2');
    </script>

    {{-- Перемикання в модалці з телефону на пошту --}}
    <script type="module">
        import {
            hideInputGroup,
            showInputGroup,
            toggleInputGroups,
        } from '{{ asset('assets/js/utils/inputGroups.js') }}';

        hideInputGroup('.input-number-group-modal');

        showInputGroup('.input-email-group-modal');

        toggleInputGroups(
            '.input-email-group-modal',
            '.input-number-group-modal',
            '.link-to-numberInputModal'
        );

        toggleInputGroups(
            '.input-number-group-modal',
            '.input-email-group-modal',
            '.link-to-emailInputModal'
        );
    </script>

    <script>
        function deleteUser(user_id) {
            console.log(user_id);
            $.ajax({
                url: '/user/delete/' + user_id,
                type: 'POST',
                data: {
                    _token: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                    _method: 'DELETE',
                },
                success: function () {
                    $('#usersDataTable').jqxGrid('updatebounddata');
                },
            });
        }
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#usersDataTable'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#usersDataTable'));
    </script>

    {{-- For new users --}}
    <script type="module" src="{{ asset('assets/js/entity/user/user-toast.js') }}"></script>

    <script type="module">
        import { switchLang } from '{{ asset('assets/js/grid/components/switch-lang.js') }}';

        let language = switchLang();
        let languageBlock = language === 'en' ? '' : '/' + language;

        // Add user modal
        $(document).ready(function () {
            $('#addUserEmailInp, #feedBackNumberInp2').on('input', function () {
                if (
                    $('#addUserEmailInp').val() === '' &&
                    $('#feedBackNumberInp2').val().length < 13
                ) {
                    $('#nextStepAuth').prop('disabled', true);
                } else {
                    $('#nextStepAuth').prop('disabled', false);
                }
            });

            $('#add-user-form').submit(function (event) {
                event.preventDefault();
                let email = $('#addUserEmailInp').val();
                let phone = $('#feedBackNumberInp2').val();
                let url = '';
                if (email) {
                    url = '/users/create?email=' + encodeURIComponent(email);
                } else if (phone) {
                    url = '/users/create?phone=' + encodeURIComponent(phone);
                }
                window.location.href = languageBlock + url;
            });
        });
    </script>
@endsection
