@extends('layouts.admin')
@section('title', __('localization.user.edit.title'))

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}"
    />
@endsection

@section('content')
    <x-layout.container id="user-id" data-id="{!! $user->id !!}">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/',
                            'name' => __('localization.user.edit.breadcrumb.users'),
                        ],
                        [
                            'url' => '/users/show/' . $user->id,
                            'name' => __('localization.user.edit.breadcrumb.view_profile'),
                            'name2' => "{$user->surname} {$user->name} {$user->patronymic}",
                        ],
                        [
                            'name' => __('localization.user.edit.breadcrumb.edit_profile'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel_edit_user"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-page-title :title="__('localization.user.edit.x_title')" />

            {{-- ==== BLOCK 1 - PERSONAL ==== --}}
            <x-card.nested id="block_1">
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.user.create.personal_data_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    {{-- Avatar --}}
                    <x-form.avatar-uploader
                        id="account"
                        name="account"
                        :imageSrc="$user->getAvatar()"
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                    />

                    {{-- Last Name --}}
                    <x-form.input-text
                        id="accountLastName"
                        name="accountLastName"
                        required
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                        label="localization.user.create.last_name"
                        placeholder="localization.user.create.last_name_placeholder"
                        value="{{ $user->surname }}"
                        data-msg="{{ __('localization.user.create.last_name_required') }}"
                    />

                    {{-- First Name --}}
                    <x-form.input-text
                        id="accountFirstName"
                        name="accountFirstName"
                        required
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                        label="localization.user.create.first_name"
                        placeholder="localization.user.create.first_name_placeholder"
                        value="{{ $user->name }}"
                        data-msg="{{ __('localization.user.create.first_name_required') }}"
                    />

                    {{-- Patronymic --}}
                    <x-form.input-text
                        id="accountPatronymic"
                        name="accountPatronymic"
                        required
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                        label="localization.user.create.patronymic"
                        placeholder="localization.user.create.patronymic_placeholder"
                        value="{{ $user->patronymic }}"
                        data-msg="{{ __('localization.user.create.patronymic_required') }}"
                    />

                    {{-- Birthday --}}
                    <x-form.date-input
                        id="birthday"
                        name="birthday"
                        class="col-12 col-md-6 mb-1 position-relative"
                        label="localization.user.create.birthday_create"
                        placeholder="localization.user.create.birthday_placeholder"
                        oninput="validateDate(this,18,'{{ app()->getLocale() }}')"
                        :required
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                        value="{{ $user->birthday }}"
                        :dateTime="false"
                        pickerClass="flatpickr-basic flatpickr-input validateDateInput"
                    />

                    {{-- Email --}}
                    <x-form.input-text
                        type="email"
                        id="accountEmail"
                        name="accountEmail"
                        required
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                        value="{{ $user->email }}"
                        label="localization.user.create.email_create"
                        placeholder="localization.user.create.email_placeholder"
                        autocomplete="disable-autocomplete"
                    />

                    {{-- Phone --}}
                    <x-form.input-text
                        type="tel"
                        id="phone"
                        name="phone"
                        required
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                        value="{{ $user->phone }}"
                        label="localization.user.create.phone_create"
                        placeholder="localization.user.create.phone_placeholder"
                        autocomplete="disable-autocomplete"
                        oninput="limitInputToNumbersWithPlus(this,13)"
                    />

                    {{-- Sex --}}
                    <x-form.select
                        id="sex"
                        name="sex"
                        :disabled="$user->id != Auth::id() && !Auth::user()->workingData?->hasRole(['super_admin', 'admin'])"
                        label="localization.user.create.sex"
                        placeholder="localization.user.create.sex_placeholder"
                    >
                        <option value="0" {{ $user && ! $user->sex ? 'selected' : '' }}>
                            {{ __('localization.user.create.sex_male') }}
                        </option>
                        <option value="1" {{ $user && $user->sex ? 'selected' : '' }}>
                            {{ __('localization.user.create.sex_female') }}
                        </option>
                    </x-form.select>

                    {{-- Change password --}}
                    <div class="row mx-0 col-12 col-md-6 mb-1">
                        <div
                            class="col-12 flex-grow-1 col-md-4 ps-0 mt-1 mt-md-0 pe-0 d-flex align-items-end"
                        >
                            <button
                                data-bs-toggle="modal"
                                id="cancel_button_2"
                                data-bs-target="#edit_user_pass"
                                type="button"
                                class="w-100 btn btn-outline-primary"
                            >
                                {{ __('localization.user.edit.change_password') }}
                            </button>
                        </div>
                    </div>

                    <div id="private-data-message" class="mt-1"></div>
                    <div class="alert-data-message"></div>
                </x-slot>
            </x-card.nested>

            {{-- ==== BLOCK 2 - WORK DATA ==== --}}
            <x-card.nested id="block_2">
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.user.create.working_data') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    {{-- Company --}}
                    {{-- <x-form.select --}}
                    {{-- id="company_id" --}}
                    {{-- name="company_id" --}}
                    {{-- :label="__('localization.user.create.company_label')" --}}
                    {{-- :placeholder="__('localization.user.create.select_company')" --}}
                    {{-- data-dictionary="company" --}}
                    {{-- data-id="{{ $user->workingData->company_id }}" --}}
                    {{-- /> --}}

                    {{-- Role --}}
                    <x-form.select
                        id="role"
                        name="role"
                        :label="__('localization.user.create.system_role')"
                        :placeholder="__('localization.user.create.select_role')"
                    >
                        @php
                            $roleNames = [
                                'super_admin' => 'create_role_system_administrator',
                                'admin' => 'create_role_administrator',
                                'user' => 'create_role_user',
                                'logistic' => 'create_role_logistics',
                                'dispatcher' => 'create_role_dispatcher',
                            ];
                        @endphp

                        @foreach ($roles as $role)
                            <option
                                value="{{ $role->name }}"
                                {{ ($user->workingData?->role[0]->id ?? null) === $role->id ? 'selected' : '' }}
                            >
                                {{ isset($roleNames[$role->name]) ? __('localization.user.create.' . $roleNames[$role->name]) : '-' }}
                            </option>
                        @endforeach
                    </x-form.select>

                    {{-- Position --}}
                    <x-form.select
                        id="position"
                        name="position"
                        :label="__('localization.user.create.company_position')"
                        :placeholder="__('localization.user.create.select_position')"
                    >
                        @php
                            $positionNames = [
                                'palet' => 'create_position_palet',
                                'complect1' => 'create_position_complect1',
                                'complect2' => 'create_position_complect2',
                                'complect3' => 'create_position_complect3',
                                'complect4' => 'create_position_complect4',
                                'complect5' => 'create_position_complect5',
                                'driver' => 'create_position_driver',
                                'logist' => 'create_position_logist',
                                'dispatcher' => 'create_position_dispatcher',
                            ];
                        @endphp

                        @foreach ($positions as $position)
                            <option
                                value="{{ $position->key }}"
                                {{ $user->workingData?->position_id === $position->id ? 'selected' : '' }}
                            >
                                {{ __('localization.user.create.' . ($positionNames[$position->key] ?? '-')) }}
                            </option>
                        @endforeach
                    </x-form.select>

                    @php
                        $selectedWarehouseIds = collect(
                            old(
                                'warehouse_ids',
                                $user && $user->workingData
                                    ? $user->workingData->warehouses->pluck('id')->all()
                                    : [],
                            ),
                        );
                    @endphp

                    {{-- Warehouses (dependent from company) --}}
                    <x-form.select
                        id="warehouses"
                        name="warehouses"
                        multiple
                        :label="__('localization.user.create.warehouses')"
                        :placeholder="__('localization.user.create.select_warehouses')"
                        data-dictionary-base="warehouses"
                        data-dependent="company_id"
                        data-dependent-param="/options?company_id"
                        :data-parent-id="$user->workingData->company_id"
                        :data-related-id="$selectedWarehouseIds"
                        data-edit="true"
                        :data-custom-options='json_encode([["id" => "all", "text" => __("localization.user.create.all_warehouses")]])'
                    />

                    <x-ui.section-divider />

                    {{-- PIN --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            {{ __('localization.user.create.pin-title') }}
                        </label>

                        <div class="input-group form-password-toggle input-group-merge">
                            <input
                                type="password"
                                class="form-control"
                                id="pin"
                                name="pin"
                                required
                                {{ $user->id == Auth::id() || Auth::user()->workingData?->hasRole(['super_admin', 'admin']) ? '' : 'disabled' }}
                                value="{{ $user->pin }}"
                                placeholder="xxxx"
                                oninput="limitInputToNumbers(this,4)"
                                autocomplete="new-password"
                            />
                            <div class="input-group-text cursor-pointer">
                                <i data-feather="eye"></i>
                            </div>
                        </div>

                        <p class="small text-muted mt-1">
                            {{ __('localization.user.create.pin-desc') }}
                        </p>
                    </div>

                    {{-- Driver fields (show only if position == driver) --}}
                    <div
                        id="driver_block"
                        class="row mx-0 p-0"
                        style="{{ $user->workingData->position?->key === 'driver' ? '' : 'display:none' }}"
                    >
                        <x-ui.section-divider />

                        <x-form.input-text
                            class="col-12 col-md-3 mb-1"
                            id="driving_license_number"
                            name="driving_license_number"
                            oninput="validateDriverLicense(this,9)"
                            value="{{ $user->workingData->driving_license_number }}"
                            :label="__('localization.user.create.driver_license_number_label')"
                            :placeholder="__('localization.user.create.driver_license_placeholder')"
                        />

                        <x-form.date-input
                            class="col-12 col-md-3 mb-1 position-relative"
                            id="driver_license_date"
                            name="driver_license_date"
                            required
                            :label="__('localization.user.create.driver_license_term')"
                            :placeholder="__('localization.user.create.driver_license_date_format')"
                            :dateTime="false"
                            :value="$user->workingData->driver_license_date"
                            pickerClass="flatpickr-basic-2"
                        />

                        <x-form.input-text
                            type="file"
                            class="col-12 col-md-6 mb-1"
                            id="driving_license"
                            name="driving_license"
                            :label="__('localization.user.create.driver_license_number_label')"
                            data-buttonText="{{ __('localization.user.create.driver_license_select_file') }}"
                        />

                        <x-form.input-text
                            class="col-12 col-md-3 mb-1"
                            id="health_book_number"
                            name="health_book_number"
                            :label="__('localization.user.create.health_book_number_label')"
                            :placeholder="__('localization.user.create.health_book_placeholder')"
                            style="text-transform: uppercase"
                            value="{{ $user->workingData->health_book_number }}"
                        />

                        <x-form.date-input
                            class="col-12 col-md-3 mb-1 position-relative"
                            id="health_book_date"
                            name="health_book_date"
                            required
                            value="{{ $user->workingData->health_book_date }}"
                            :label="__('localization.user.create.health_book_term')"
                            :placeholder="__('localization.user.create.health_book_date_format')"
                            :dateTime="false"
                            pickerClass="flatpickr-basic-2"
                        />

                        <x-form.input-text
                            type="file"
                            class="col-12 col-md-6 mb-1"
                            id="health_book"
                            name="health_book"
                            :label="__('localization.user.create.upload_health_book')"
                            data-buttonText="{{ __('localization.user.create.health_book_select_file') }}"
                        />
                    </div>

                    <div id="work-data-message"></div>
                    <input type="hidden" id="need_file" value="true" />
                </x-slot>
            </x-card.nested>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="save"
                    class="btn btn-primary mb-2 float-end"
                    :text="__('localization.user.edit.save')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <div
        class="modal fade text-start"
        id="edit_user_pass"
        tabindex="-1"
        aria-labelledby="myModalLabel6"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
            <div class="modal-content">
                <div class="card popup-card p-4">
                    <h2 class="fw-bolder text-center">
                        {{ __('localization.user.edit.password_modal.title') }}
                    </h2>
                    <div class="card-body row mx-0 p-0">
                        <div class="col-12 mb-1">
                            <label class="form-label">
                                {{ __('localization.user.edit.password_modal.old_password') }}
                            </label>
                            <div class="input-group form-password-toggle input-group-merge">
                                <input
                                    type="password"
                                    class="form-control"
                                    required
                                    name="password"
                                    id="old-password"
                                    autocomplete="off"
                                    placeholder="{{ __('localization.user.edit.password_modal.enter_old_password') }}"
                                />
                                <div class="input-group-text cursor-pointer">
                                    <i data-feather="eye"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-1">
                            <label class="form-label">
                                {{ __('localization.user.edit.password_modal.new_password') }}
                            </label>
                            <div class="input-group form-password-toggle input-group-merge">
                                <input
                                    type="password"
                                    class="form-control"
                                    name="new_password"
                                    placeholder="{{ __('localization.user.edit.password_modal.enter_new_password') }}"
                                    required
                                    autocomplete="off"
                                />
                                <div class="input-group-text cursor-pointer">
                                    <i data-feather="eye"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-1">
                            <label class="form-label">
                                {{ __('localization.user.edit.password_modal.confirm_new_password') }}
                            </label>
                            <div class="input-group form-password-toggle input-group-merge">
                                <input
                                    type="password"
                                    class="form-control"
                                    required
                                    name="confirm_password"
                                    placeholder="{{ __('localization.user.edit.password_modal.confirm_new_password') }}"
                                    autocomplete="off"
                                />
                                <div class="input-group-text cursor-pointer">
                                    <i data-feather="eye"></i>
                                </div>
                            </div>
                        </div>

                        <div id="change-password-message"></div>

                        <div class="col-12 mt-1">
                            <div class="d-flex float-end">
                                <button
                                    type="button"
                                    class="btn btn-link cancel-btn"
                                    data-bs-dismiss="modal"
                                >
                                    {{ __('localization.user.edit.password_modal.cancel') }}
                                </button>
                                <button type="button" id="change-password" class="btn btn-primary">
                                    {{ __('localization.user.edit.password_modal.change') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-cancel-modal
        id="cancel_edit_user"
        route="/users/show/{{ $user->id }}"
        title="{{ __('localization.user.edit.cancel_modal.title') }}"
        content="{!! __('localization.user.edit.cancel_modal.confirmation') !!}"
        cancel-text="{{ __('localization.user.edit.cancel_modal.cancel') }}"
        confirm-text="{{ __('localization.user.edit.cancel_modal.submit') }}"
    />
@endsection

@section('page-script')
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/user/account.settings.js') }}"></script>

    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script>
        window.onload = function() {

            @if($user->workingData->position && $user->workingData->position->key === 'driver')

            const fileInput = document.querySelector('#health_book');

            const myFile = new File([''], '{!! $healthBookFile->name !!}', {
                type: 'text/plain',
                lastModified: new Date(),
            });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(myFile);
            fileInput.files = dataTransfer.files;

            const fileInput2 = document.querySelector('#driving_license');

            const myFile2 = new File([''], '{!! $drivingLicenseFile->name !!}', {
                type: 'text/plain',
                lastModified: new Date(),
            });

            const dataTransfer2 = new DataTransfer();
            dataTransfer2.items.add(myFile2);
            fileInput2.files = dataTransfer2.files;

            $('#need_file').val('false');
            @endif
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementsByClassName('validateDateInput')[0];
            const dateInputTwo = document.getElementsByClassName('validateDateInput')[1];
            const minAge = 18;
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            validateDate(dateInput, minAge, lang);
            validateDate(dateInputTwo, minAge, lang);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            flatpickr('.flatpickr-basic-2', {
                dateFormat: 'Y-m-d',
                locale: lang,
            });
        });
    </script>

    <script type="module">
        import { initSelectAllLogic } from '{{ asset('assets/js/utils/dictionary/selectAllLogic.js') }}';

        document.addEventListener('DOMContentLoaded', () => {
            initSelectAllLogic(['#warehouses']);
        });
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/utils/dictionary/selectDictionaryRelated.js') }}"
    ></script>
@endsection
