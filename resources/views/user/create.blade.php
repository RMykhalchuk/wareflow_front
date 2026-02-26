@extends('layouts.admin')

@section('title', __('localization.user.create.title'))

@section('page-style')
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

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
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        ['url' => '/', 'name' => __('localization.user.create.breadcrumb.users')],
                        ['name' => __('localization.user.create.breadcrumb.title')],
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
            <x-page-title :title="__('localization.user.create.x_title')" />

            {{-- ==== BLOCK 1 - PERSONAL ==== --}}
            <x-card.nested id="block_1">
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.user.create.personal_data_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    {{-- Hidden field new_user --}}
                    <input hidden id="new_user" name="new_user" value="{{ $user ? 0 : 1 }}" />

                    {{-- Avatar --}}
                    <x-form.avatar-uploader
                        id="account"
                        name="account"
                        :imageSrc="$user && $user->avatar_type
                            ? asset('file/uploads/user/avatars/'.$user->id.'.'.$user->avatar_type)
                        : asset('assets/icons/entity/user/avatar_empty.png')"
                        :disabled="$user ? true : false"
                    />

                    {{-- Last Name --}}
                    <x-form.input-text
                        id="accountLastName"
                        name="accountLastName"
                        required
                        :disabled="$user ? true : false"
                        label="localization.user.create.last_name"
                        placeholder="localization.user.create.last_name_placeholder"
                        value="{{ $user?->surname }}"
                        data-msg="{{ __('localization.user.create.last_name_required') }}"
                    />

                    {{-- First Name --}}
                    <x-form.input-text
                        id="accountFirstName"
                        name="accountFirstName"
                        required
                        :disabled="$user ? true : false"
                        label="localization.user.create.first_name"
                        placeholder="localization.user.create.first_name_placeholder"
                        value="{{ $user?->name }}"
                        data-msg="{{ __('localization.user.create.first_name_required') }}"
                    />

                    {{-- Patronymic --}}
                    <x-form.input-text
                        id="accountPatronymic"
                        name="accountPatronymic"
                        required
                        :disabled="$user ? true : false"
                        label="localization.user.create.patronymic"
                        placeholder="localization.user.create.patronymic_placeholder"
                        value="{{ $user?->patronymic }}"
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
                        :disabled="$user && $user->birthday"
                        value="{{ $user?->birthday }}"
                        :dateTime="false"
                        pickerClass="flatpickr-basic flatpickr-input validateDateInput"
                    />

                    {{-- Email --}}
                    <x-form.input-text
                        type="email"
                        id="accountEmail"
                        name="accountEmail"
                        required
                        :disabled="$user ? true : false"
                        value="{{ request('email') ?? $user?->email }}"
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
                        :disabled="$user ? true : false"
                        value="{{ request('phone') ?? $user?->phone }}"
                        label="localization.user.create.phone_create"
                        placeholder="localization.user.create.phone_placeholder"
                        autocomplete="disable-autocomplete"
                        oninput="limitInputToNumbersWithPlus(this,13)"
                    />

                    {{-- Sex --}}
                    <x-form.select
                        id="sex"
                        name="sex"
                        :disabled="$user ? true : false"
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

                    <!-- Temporary Password -->
                    <div class="row mx-0 col-12 col-md-6 mb-1">
                        <div class="col-12 p-0 col-md-8">
                            <label class="form-label">
                                {{ __('localization.user.create.password') }}
                            </label>

                            <div class="input-group form-password-toggle input-group-merge">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    required
                                    {{ $user ? '' : '' }}
                                    placeholder="{{ __('localization.user.create.password_placeholder') }}"
                                    autocomplete="new-password"
                                />

                                <div class="input-group-text cursor-pointer">
                                    <i data-feather="eye"></i>
                                </div>
                            </div>
                        </div>

                        <div
                            class="col-12 flex-grow-1 col-md-4 ps-0 ps-md-1 mt-1 mt-md-0 pe-0 d-flex align-items-end"
                        >
                            <button
                                {{ $user ? '' : '' }}
                                id="generate-password"
                                class="btn btn-outline-primary w-100"
                            >
                                {{ __('localization.user.create.generate_password') }}
                            </button>
                        </div>
                    </div>

                    <div id="private-data-message" class="mt-1"></div>
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
                            <option value="{{ $role->name }}">
                                {{ __('localization.user.create.' . ($roleNames[$role->name] ?? '-')) }}
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
                            <option value="{{ $position->key }}">
                                {{ __('localization.user.create.' . ($positionNames[$position->key] ?? '-')) }}
                            </option>
                        @endforeach
                    </x-form.select>

                    {{-- Warehouses (dependent from company) --}}
                    <x-form.select
                        id="warehouses"
                        name="warehouses"
                        multiple
                        :label="__('localization.user.create.warehouses')"
                        :placeholder="__('localization.user.create.select_warehouses')"
                        data-dependent="company_id"
                        data-dependent-param="/options?company_id"
                        data-dictionary-base="warehouses"
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
                    <div id="driver_block" class="row mx-0 p-0" style="display: none">
                        <x-ui.section-divider />

                        <x-form.input-text
                            class="col-12 col-md-3 mb-1"
                            id="driving_license_number"
                            name="driving_license_number"
                            oninput="validateDriverLicense(this,9)"
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
                        />

                        <x-form.date-input
                            class="col-12 col-md-3 mb-1 position-relative"
                            id="health_book_date"
                            name="health_book_date"
                            required
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
                </x-slot>
            </x-card.nested>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="create_user"
                    class="btn btn-primary mb-2 float-end"
                    :text="__('localization.user.create.btn_add_user')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    {{-- Cancel modal --}}
    <x-cancel-modal
        id="cancel_edit_user"
        route="/users/all"
        title="{{ __('localization.user.create.cancel_modal.title') }}"
        content="{!! __('localization.user.create.cancel_modal.confirmation') !!}"
        cancel-text="{{ __('localization.user.create.cancel_modal.cancel') }}"
        confirm-text="{{ __('localization.user.create.cancel_modal.submit') }}"
    />
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/user/create-user.js') }}"></script>

    <script src="{{ asset('vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-input-mask.js') }}"></script>

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
