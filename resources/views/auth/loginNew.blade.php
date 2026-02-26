<!-- @extends('layouts.app') -->

@section('title', __('localization.auth_index_title'))

@section('content')
    <!-- Сторінка авторизації (входу в акаунт) -->
    <div class="auth auth_content container px-0 px-md-0 h-100 mx-2">
        <div class="row mx-0 h-100 add_shadow">
            <!-- Контейнер з формою -->
            <div
                class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
            >
                <div class="px-2">
                    <div class="d-flex justify-content-between">
                        <div class="cont-logo d-flex mb-2">
                            <img
                                width="25px"
                                src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                alt="logo"
                            />
                            <p class="h5 fw-bolder my-auto ms-25">{{ config('app.name') }}</p>
                        </div>
                        <x-layout.language-switcher />
                    </div>

                    <h2 class="h1 fw-bolder mb-2">
                        {{ __('localization.auth_index_auth_sign_in_title') }}
                    </h2>
                </div>

                <form id="login-form" class="auth-login-form w-100 h-100 d-flex flex-column px-2">
                    <div class="mb-1 input-email-group">
                        <label class="form-label" for="login-email-auth">
                            {{ __('localization.auth_index_auth_email_label') }}
                        </label>
                        <input
                            class="form-control"
                            style="margin-bottom: 5px"
                            id="login-email-auth"
                            type="email"
                            name="login"
                            placeholder="{{ __('localization.auth_index_auth_email_placeholder') }}"
                            aria-describedby="login-email"
                            autofocus=""
                            tabindex="1"
                        />
                        <a
                            href="#"
                            class="text-secondary text-decoration-underline link-to-numberInput"
                        >
                            {{ __('localization.auth_index_auth_use_phone') }}
                        </a>
                    </div>

                    <!-- Кастомізований інпут для номеру телефону різних країн -->
                    <div
                        class="input-number-group inpSelectNumCountry"
                        style="padding-top: 2px; display: none"
                    >
                        <div class="mb-1 d-flex flex-column">
                            <label class="form-label" for="authNumberInp">
                                {{ __('localization.auth_index_auth_phone_label') }}
                            </label>
                            <input
                                class="form-control input-number-country"
                                id="authNumberInp"
                                name="login"
                                aria-describedby="authNumberInp"
                                autofocus=""
                            />
                            <a
                                href="#"
                                class="text-secondary text-decoration-underline link-to-emailInput"
                                style="margin-top: 5px"
                            >
                                {{ __('localization.auth_index_auth_use_email') }}
                            </a>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="login-password">
                                {{ __('localization.auth_index_auth_password_label') }}
                            </label>
                        </div>

                        <div class="input-group input-group-merge form-password-toggle mb-1">
                            <input
                                class="form-control form-control-merge"
                                id="login-password"
                                type="password"
                                name="password"
                                placeholder="{{ __('localization.auth_index_auth_password_placeholder') }}"
                                aria-describedby="login-password"
                                minlength="8"
                                tabindex="2"
                            />
                            <span class="input-group-text cursor-pointer">
                                <i data-feather="eye"></i>
                            </span>
                        </div>
                    </div>

                    <div
                        id="login-errors"
                        class="mb-1 alert alert-danger alert-register-form d-none"
                        role="alert"
                    >
                        {{ __('localization.auth_index_auth_error_message') }}
                    </div>

                    <div class="d-flex flex-wrap justify-content-between">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                id="remember-me"
                                type="checkbox"
                                tabindex="3"
                                name="remember"
                            />
                            <label class="form-check-label" for="remember-me">
                                {{ __('localization.auth_index_auth_remember_me') }}
                            </label>
                        </div>

                        <a
                            href="#"
                            class="text-secondary text-decoration-underline link-to-passRecovery"
                        >
                            <small>{{ __('localization.auth_index_auth_forgot_password') }}</small>
                        </a>
                    </div>

                    <div
                        class="h-100 w-100 d-flex justify-content-between justify-content-lg-end align-items-end"
                    >
                        <p class="mb-0 d-md-block d-lg-none">
                            {{ __('localization.auth_index_auth_no_account') }}
                            <a href="#" class="fw-bold link-to-register">
                                {{ __('localization.auth_index_auth_register') }}
                            </a>
                        </p>
                        <button
                            type="submit"
                            class="btn btn-primary disabled btnDisabled"
                            tabindex="4"
                        >
                            {{ __('localization.auth_index_auth_sign_in_button') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- фоновий контейнер -->
            <div
                class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
            >
                <div class="px-4 py-3">
                    <img
                        class=""
                        src="{{ asset('assets/icons/entity/auth/auth_laptop_sign_in.svg') }}"
                        alt="logo"
                    />
                </div>
                <p>{!! __('localization.auth_index_auth_welcome_back') !!}</p>
                <p>
                    {{ __('localization.auth_index_auth_no_account') }}
                    <a href="#" class="fw-bold link-to-register">
                        {{ __('localization.auth_index_auth_register') }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Візард з сторінками реєстрації -->
    <div class="register mx-2 d-none auth_content container h-100">
        <!-- Всередині змінюється контент по кнопках Далі та Назад  -->
        <div class="w-100 h-100">
            <!-- Перша сторінка реєстрації  -->
            <div class="h-100" id="register_page">
                <div class="row h-100 add_shadow">
                    <!-- Контейнер з формою  -->
                    <div
                        class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
                    >
                        <div class="px-2">
                            <div class="d-flex justify-content-between">
                                <div class="cont-logo d-flex mb-2">
                                    <img
                                        width="25px"
                                        src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                        alt="logo"
                                    />
                                    <p class="h5 fw-bolder my-auto ms-25">
                                        {{ config('app.name') }}
                                    </p>
                                </div>
                                <x-layout.language-switcher />
                            </div>

                            <h2 class="h1 fw-bolder mb-2">
                                {{ __('localization.auth_index_register_page_register_title') }}
                            </h2>
                        </div>

                        <form
                            id="register-login-form"
                            class="register-form w-100 h-100 d-flex flex-column px-2"
                        >
                            <div class="mb-1 input-email-group">
                                <label class="form-label" for="registerEmailInp">
                                    {{ __('localization.auth_index_register_page_email_label') }}
                                </label>
                                <input
                                    class="form-control"
                                    id="registerEmailInp"
                                    type="email"
                                    name="login"
                                    placeholder="{{ __('localization.auth_index_register_page_email_placeholder') }}"
                                    style="margin-bottom: 5px"
                                    aria-describedby="registerEmailInp"
                                    autofocus=""
                                    tabindex="1"
                                />
                                <a
                                    href="#"
                                    class="text-secondary text-decoration-underline link-to-numberInput"
                                >
                                    {{ __('localization.auth_index_register_page_use_phone') }}
                                </a>
                            </div>

                            <!-- Кастомізований інпут для номеру телефону різних країн -->
                            <div
                                class="input-number-group inpSelectNumCountry"
                                style="padding-top: 2px"
                            >
                                <div class="mb-1 d-flex flex-column">
                                    <label class="form-label" for="registerNumberInp">
                                        {{ __('localization.auth_index_register_page_phone_label') }}
                                    </label>
                                    <input
                                        class="form-control input-number-country"
                                        id="registerNumberInp"
                                        name="login"
                                        aria-describedby="registerNumberInp"
                                        autofocus=""
                                    />
                                    <a
                                        href="#"
                                        class="text-secondary text-decoration-underline link-to-emailInput"
                                        style="margin-top: 5px"
                                    >
                                        {{ __('localization.auth_index_register_page_use_email') }}
                                    </a>
                                </div>
                            </div>

                            <div class="mb-1">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="registerPassword">
                                        {{ __('localization.auth_index_register_page_password_label') }}
                                    </label>
                                </div>

                                <div class="input-group input-group-merge form-password-toggle">
                                    <input
                                        class="form-control form-control-merge"
                                        id="registerPassword"
                                        type="password"
                                        name="password"
                                        placeholder="{{ __('localization.auth_index_register_page_password_placeholder') }}"
                                        minlength="8"
                                        aria-describedby="registerPassword"
                                        tabindex="2"
                                    />
                                    <span class="input-group-text cursor-pointer">
                                        <i data-feather="eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="registerPasswordRepeat">
                                        {{ __('localization.auth_index_register_page_confirm_password_label') }}
                                    </label>
                                </div>

                                <div class="container-confirmPass-and-alert">
                                    <div
                                        class="input-group input-group-merge form-password-toggle mb-1"
                                    >
                                        <input
                                            class="form-control form-control-merge"
                                            id="registerPasswordRepeat"
                                            type="password"
                                            name="confirmPassword"
                                            placeholder="{{ __('localization.auth_index_register_page_confirm_password_placeholder') }}"
                                            minlength="8"
                                            data-form="register-form"
                                            aria-describedby="registerPasswordRepeat"
                                            tabindex="2"
                                        />
                                        <span class="input-group-text cursor-pointer">
                                            <i data-feather="eye"></i>
                                        </span>
                                    </div>
                                    <div
                                        id="register-errors"
                                        class="alert alert-danger alert-register-form d-none"
                                        role="alert"
                                    >
                                        {{ __('localization.auth_index_register_page_password_mismatch') }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="h-100 w-100 d-flex justify-content-between justify-content-lg-end align-items-end"
                            >
                                <p class="d-md-block mb-0 d-lg-none">
                                    {{ __('localization.auth_index_register_page_have_account') }}
                                    <a href="#" class="fw-bold link-to-auth">
                                        {{ __('localization.auth_index_register_page_sign_in') }}
                                    </a>
                                </p>
                                <button
                                    type="submit"
                                    class="btn btn-primary disabled btnDisabled"
                                    tabindex="4"
                                    id="register-send-code-button"
                                >
                                    <span class="align-middle d-sm-inline-block">
                                        {{ __('localization.auth_index_register_page_next') }}
                                    </span>
                                    <i
                                        data-feather="arrow-right"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- фоновий контейнер  -->
                    <div
                        class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
                    >
                        <div class="px-4 py-3">
                            <img
                                class=""
                                src="{{ asset('assets/icons/entity/auth/auth_laptop_signup.svg') }}"
                                alt="logo"
                            />
                        </div>
                        <p>
                            {{ __('localization.auth_index_register_page_terms_agreement') }}
                            <a class="text-secondary text-decoration-underline" href="#">
                                {{ __('localization.auth_index_register_page_terms') }}
                            </a>
                            {{ __('localization.auth_index_register_page_and') }}
                            <a class="text-secondary text-decoration-underline" href="#">
                                {{ __('localization.auth_index_register_page_privacy_policy') }}
                            </a>
                            .
                        </p>
                        <p>
                            {{ __('localization.auth_index_register_page_have_account') }}
                            <a href="#" class="fw-bold link-to-auth">
                                {{ __('localization.auth_index_register_page_sign_in') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Друга сторінка реєстрації  -->
            <div class="h-100 d-none" id="register_code">
                <!-- Перевірка коду по емейлу -->
                <div class="row h-100 add_shadow register-code-by-email-content d-none">
                    <!-- Контейнер з формою  -->
                    <div
                        class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
                    >
                        <div class="px-2">
                            <div class="d-flex justify-content-between">
                                <div class="cont-logo d-flex mb-2">
                                    <img
                                        width="25px"
                                        src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                        alt="logo"
                                    />
                                    <p class="h5 fw-bolder my-auto ms-25">
                                        {{ config('app.name') }}
                                    </p>
                                </div>
                            </div>
                            <h2 class="h1 fw-bolder mb-2">
                                {{ __('localization.auth_index_register_code_by_email_register_title') }}
                            </h2>
                        </div>

                        <form
                            id="register-send-email-code"
                            class="register-code-by-email-form w-100 h-100 d-flex flex-column px-2"
                        >
                            <div class="mb-1">
                                <label class="form-label mb-1" for="login-email">
                                    {{ __('localization.auth_index_register_code_by_email_email_sent') }}
                                    <span class="fw-medium-c" id="writeCurrentMail">
                                        example@gmail.com
                                    </span>
                                </label>
                                <div
                                    id="otp-register-code-by-email"
                                    data-formclassname=".register-code-by-email-form"
                                    class="inputs d-flex justify-content-start"
                                >
                                    <input
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="first-byEmail-reg"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="second-byEmail-reg"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="third-byEmail-reg"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="text-center form-control otp__input"
                                        type="number"
                                        id="fourth-byEmail-reg"
                                        maxlength="1"
                                    />
                                </div>
                                <div class="alert alert-danger d-none mt-2 mb-1" role="alert">
                                    {{ __('localization.auth_index_register_code_by_email_error_text') }}
                                </div>
                            </div>

                            <a
                                id="refresh-email-code"
                                href="#"
                                class="text-secondary text-decoration-underline"
                            >
                                <small>
                                    {{ __('localization.auth_index_register_code_by_email_resend_code') }}
                                </small>
                            </a>
                            <div class="h-100 w-100 d-flex justify-content-between align-items-end">
                                <button type="button" class="btn btn-to-registerPage" tabindex="4">
                                    <i
                                        data-feather="arrow-left"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                    <span class="align-middle d-sm-inline-block text-secondary">
                                        {{ __('localization.auth_index_register_code_by_email_back') }}
                                    </span>
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary disabled btnDisabled"
                                    tabindex="4"
                                >
                                    <span class="align-middle d-sm-inline-block">
                                        {{ __('localization.auth_index_register_code_by_email_next') }}
                                    </span>
                                    <i
                                        data-feather="arrow-right"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- фоновий контейнер  -->
                    <div
                        class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
                    >
                        <div class="px-4 py-3">
                            <img
                                class=""
                                src="{{ asset('assets/icons/entity/auth/auth_laptop_signup_code.svg') }}"
                                alt="logo"
                            />
                        </div>
                        <p>
                            {{ __('localization.auth_index_register_code_by_email_terms_agreement') }}
                            <a class="text-secondary text-decoration-underline" href="#">
                                {{ __('localization.auth_index_register_code_by_email_terms') }}
                            </a>
                            {{ __('localization.auth_index_register_code_by_email_and') }}
                            <a class="text-secondary text-decoration-underline" href="#">
                                {{ __('localization.auth_index_register_code_by_email_privacy_policy') }}
                            </a>
                            .
                        </p>
                        <p>
                            {{ __('localization.auth_index_register_code_by_email_have_account') }}
                            <a href="#" class="fw-bold link-to-auth">
                                {{ __('localization.auth_index_register_code_by_email_sign_in') }}
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Перевірка коду по телефоні  -->
                <div class="row h-100 add_shadow register-code-by-phone-content d-none">
                    <!-- Контейнер з формою  -->
                    <div
                        class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
                    >
                        <div class="px-2">
                            <div class="cont-logo d-flex mb-2">
                                <img
                                    width="25px"
                                    src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                    alt="logo"
                                />
                                <p class="h5 fw-bolder my-auto ms-25">{{ config('app.name') }}</p>
                            </div>
                            <h2 class="h1 fw-bolder mb-2">
                                {{ __('localization.auth_index_register_code_by_phone_register_title') }}
                            </h2>
                        </div>

                        <form
                            id="register-send-phone-code"
                            class="register-code-by-phone-form w-100 h-100 d-flex flex-column px-2"
                        >
                            <div class="mb-1">
                                <label class="form-label mb-1" for="login-email">
                                    {{ __('localization.auth_index_register_code_by_phone_phone_sent') }}
                                    <span class="fw-medium-c" id="showWriteNumberRegister">
                                        +38 (088) 888 88 88
                                    </span>
                                </label>
                                <div
                                    id="otp-register-code-by-phone"
                                    data-formclassname=".register-code-by-phone-form"
                                    class="inputs d-flex justify-content-start"
                                >
                                    <input
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="first-byPhone-reg"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="second-byPhone-reg"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="third-byPhone-reg"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="text-center form-control otp__input"
                                        type="number"
                                        id="fourth-byPhone-reg"
                                        maxlength="1"
                                    />
                                </div>
                                <div class="alert alert-danger d-none mt-2 mb-1" role="alert">
                                    {{ __('localization.auth_index_register_code_by_phone_error_text') }}
                                </div>
                            </div>

                            <a
                                id="refresh-phone-code"
                                href="#"
                                class="text-secondary text-decoration-underline"
                            >
                                <small>
                                    {{ __('localization.auth_index_register_code_by_phone_resend_code') }}
                                </small>
                            </a>
                            <div class="h-100 w-100 d-flex justify-content-between align-items-end">
                                <button type="button" class="btn btn-to-registerPage" tabindex="4">
                                    <i
                                        data-feather="arrow-left"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                    <span class="align-middle d-sm-inline-block text-secondary">
                                        {{ __('localization.auth_index_register_code_by_phone_back') }}
                                    </span>
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary disabled btnDisabled"
                                    tabindex="4"
                                >
                                    <span class="align-middle d-sm-inline-block">
                                        {{ __('localization.auth_index_register_code_by_phone_next') }}
                                    </span>
                                    <i
                                        data-feather="arrow-right"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- фоновий контейнер  -->
                    <div
                        class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
                    >
                        <div class="px-4 py-3">
                            <img
                                class=""
                                src="{{ asset('assets/icons/entity/auth/auth_laptop_signup_code.svg') }}"
                                alt="logo"
                            />
                        </div>
                        <p>
                            {{ __('localization.auth_index_register_code_by_phone_terms_agreement') }}
                            <a class="text-secondary text-decoration-underline" href="#">
                                {{ __('localization.auth_index_register_code_by_phone_terms') }}
                            </a>
                            {{ __('localization.auth_index_register_code_by_phone_and') }}
                            <a class="text-secondary text-decoration-underline" href="#">
                                {{ __('localization.auth_index_register_code_by_phone_privacy_policy') }}
                            </a>
                            .
                        </p>

                        <p>
                            {{ __('localization.auth_index_register_code_by_phone_have_account') }}
                            <a href="#" class="fw-bold link-to-auth">
                                {{ __('localization.auth_index_register_code_by_phone_sign_in') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Сторінки ВІДНОВЛЕННЯ ПАРОЛЮ -->
    <div class="passRecovery d-none auth_content container mx-2 h-100">
        <!-- Всередині змінюється контент по кнопках Далі та Назад  -->
        <div class="w-100 h-100">
            <!-- Перша сторінка ВІДНОВЛЕННЯ ПАРОЛЮ  -->
            <div class="h-100" id="passRecovery_page">
                <div class="row h-100 add_shadow">
                    <!-- Контейнер з формою  -->
                    <div
                        class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
                    >
                        <div class="px-2">
                            <div class="d-flex justify-content-between">
                                <div class="cont-logo d-flex mb-2">
                                    <img
                                        width="25px"
                                        src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                        alt="logo"
                                    />
                                    <p class="h5 fw-bolder my-auto ms-25">
                                        {{ config('app.name') }}
                                    </p>
                                </div>
                                <x-layout.language-switcher />
                            </div>

                            <h2 class="h1 fw-bolder mb-2">
                                {{ __('localization.auth_index_pass_recovery_page_title') }}
                            </h2>
                            <p class="mb-2">
                                {{ __('localization.auth_index_pass_recovery_page_instructions') }}
                            </p>
                        </div>

                        <form
                            id="reset-password-form"
                            class="passRecovery-form w-100 h-100 d-flex flex-column px-2"
                        >
                            <div class="mb-1 input-email-group">
                                <label class="form-label" for="passRecoveryEmailInp">
                                    {{ __('localization.auth_index_pass_recovery_page_enter_email') }}
                                </label>
                                <input
                                    class="form-control"
                                    id="passRecoveryEmailInp"
                                    type="email"
                                    name="login"
                                    placeholder="{{ __('localization.auth_index_pass_recovery_page_email_placeholder') }}"
                                    aria-describedby="passRecoveryEmailInp"
                                    autofocus=""
                                    tabindex="1"
                                    style="margin-bottom: 5px"
                                />
                                <a
                                    href="#"
                                    class="text-secondary text-decoration-underline link-to-numberInput"
                                >
                                    {{ __('localization.auth_index_pass_recovery_page_phone_link') }}
                                </a>
                            </div>

                            <!-- Кастомізований інпут для номеру телефону різних країн -->
                            <div
                                class="input-number-group inpSelectNumCountry"
                                style="padding-top: 2px"
                            >
                                <div class="mb-1 d-flex flex-column">
                                    <label class="form-label" for="passRecoveryNumberInp">
                                        {{ __('localization.auth_index_pass_recovery_page_enter_phone') }}
                                    </label>
                                    <input
                                        class="form-control input-number-country"
                                        id="passRecoveryNumberInp"
                                        name="login"
                                        aria-describedby="passRecoveryNumberInp"
                                        autofocus=""
                                    />
                                    <a
                                        href="#"
                                        class="text-secondary text-decoration-underline link-to-emailInput"
                                        style="margin-top: 5px"
                                    >
                                        {{ __('localization.auth_index_pass_recovery_page_email_link') }}
                                    </a>
                                </div>
                            </div>

                            <div class="my-2 alert alert-danger d-none" role="alert">
                                {{ __('localization.auth_index_pass_recovery_page_error_message') }}
                            </div>

                            <div
                                class="h-100 w-100 d-flex justify-content-between justify-content-lg-end align-items-end"
                            >
                                <p class="mb-0 d-md-block d-lg-none">
                                    {{ __('localization.auth_index_pass_recovery_page_remember_password') }}
                                    <a href="#" class="fw-bold link-to-auth">
                                        {{ __('localization.auth_index_pass_recovery_page_login_link') }}
                                    </a>
                                </p>
                                <button
                                    type="submit"
                                    class="btn btn-primary disabled btnDisabled"
                                    tabindex="4"
                                >
                                    <span class="align-middle d-sm-inline-block">
                                        {{ __('localization.auth_index_pass_recovery_page_next_button') }}
                                    </span>
                                    <i
                                        data-feather="arrow-right"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- фоновий контейнер  -->
                    <div
                        class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
                    >
                        <div class="px-4 py-3">
                            <img
                                class=""
                                src="{{ asset('assets/icons/entity/auth/auth_laptop_tools.svg') }}"
                                alt="logo"
                            />
                        </div>
                        <p>
                            {{ __('localization.auth_index_pass_recovery_page_contact_admin_1') }}
                            <a
                                class="text-secondary text-decoration-underline"
                                data-bs-toggle="modal"
                                data-bs-target="#modalForSendSupport"
                                href="#"
                            >
                                {{ __('localization.auth_index_pass_recovery_page_contact_admin_2') }}
                            </a>
                            {{ __('localization.auth_index_pass_recovery_page_contact_admin_3') }}
                        </p>

                        <p>
                            {{ __('localization.auth_index_pass_recovery_page_remember_password') }}
                            <a href="#" class="fw-bold link-to-auth">
                                {{ __('localization.auth_index_pass_recovery_page_login_link') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Друга сторінка ВІДНОВЛЕННЯ ПАРОЛЮ  -->
            <div class="h-100 d-none" id="passRecovery_code">
                <!-- Перевірка коду по емейлу -->
                <div class="row h-100 add_shadow passRecovery-code-by-email-content d-none">
                    <!-- Контейнер з формою  -->
                    <div
                        class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
                    >
                        <div class="px-2">
                            <div class="d-flex justify-content-between">
                                <div class="cont-logo d-flex mb-2">
                                    <img
                                        width="25px"
                                        src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                        alt="logo"
                                    />
                                    <p class="h5 fw-bolder my-auto ms-25">
                                        {{ config('app.name') }}
                                    </p>
                                </div>
                            </div>
                            <h2 class="h1 fw-bolder mb-2">
                                {{ __('localization.auth_index_pass_recovery_code_by_email_title') }}
                            </h2>
                        </div>

                        <form
                            id="reset-password-code-email-form"
                            class="passRecovery-code-by-email-form w-100 h-100 d-flex flex-column px-2"
                        >
                            <div class="mb-1">
                                <label class="form-label mb-1" for="passRecovery-by-email">
                                    {{ __('localization.auth_index_pass_recovery_code_by_email_confirmation_text') }}
                                    <span class="fw-medium-c" id="writeCurrentMailRestore">
                                        example@gmail.com
                                    </span>
                                    {{ __('localization.auth_index_pass_recovery_code_by_email_instructions') }}
                                </label>
                                <div
                                    id="otp-passRecovery-code-by-email"
                                    data-formclassname=".passRecovery-code-by-email-form"
                                    class="inputs d-flex justify-content-start"
                                >
                                    <input
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="first-byEmail-pass"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="second-byEmail-pass"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="third-byEmail-pass"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="text-center form-control otp__input"
                                        type="number"
                                        id="fourth-byEmail-pass"
                                        maxlength="1"
                                    />
                                </div>
                                <div class="my-2 alert alert-danger d-none" role="alert">
                                    {{ __('localization.auth_index_pass_recovery_code_by_email_error_message') }}
                                </div>
                            </div>

                            <a
                                id="refresh-password-phone-code"
                                href="#"
                                class="text-secondary text-decoration-underline"
                            >
                                <small>
                                    {{ __('localization.auth_index_pass_recovery_code_by_email_resend_code') }}
                                </small>
                            </a>
                            <div class="h-100 w-100 d-flex justify-content-between align-items-end">
                                <button
                                    type="button"
                                    class="btn btn-to-passRecoveryPage"
                                    tabindex="4"
                                >
                                    <i
                                        data-feather="arrow-left"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                    <span class="align-middle d-sm-inline-block text-secondary">
                                        {{ __('localization.auth_index_pass_recovery_code_by_email_back_button') }}
                                    </span>
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary disabled btnDisabled"
                                    tabindex="4"
                                >
                                    <span class="align-middle d-sm-inline-block">
                                        {{ __('localization.auth_index_pass_recovery_code_by_email_next_button') }}
                                    </span>
                                    <i
                                        data-feather="arrow-right"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- фоновий контейнер  -->
                    <div
                        class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
                    >
                        <div class="px-4 py-3">
                            <img
                                class=""
                                src="{{ asset('assets/icons/entity/auth/auth_laptop_signup_code.svg') }}"
                                alt="logo"
                            />
                        </div>
                        <p>
                            {{ __('localization.auth_index_pass_recovery_code_by_email_contact_admin') }}
                            <a
                                class="text-secondary text-decoration-underline"
                                data-bs-toggle="modal"
                                data-bs-target="#modalForSendSupport"
                                href="#"
                            >
                                {{ __('localization.auth_index_pass_recovery_code_by_email_contact_admin_link') }}
                            </a>
                            {{ __('localization.auth_index_pass_recovery_code_by_email_contact_solve_problem') }}
                        </p>
                        <p>
                            {{ __('localization.auth_index_pass_recovery_code_by_email_remember_password') }}
                            <a href="#" class="fw-bold link-to-auth">
                                {{ __('localization.auth_index_pass_recovery_code_by_email_login_link') }}
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Перевірка коду по телефону -->
                <div class="row h-100 add_shadow passRecovery-code-by-phone-content d-none">
                    <!-- Контейнер з формою  -->
                    <div
                        class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
                    >
                        <div class="px-2">
                            <div class="d-flex justify-content-between">
                                <div class="cont-logo d-flex mb-2">
                                    <img
                                        width="25px"
                                        src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                        alt="logo"
                                    />
                                    <p class="h5 fw-bolder my-auto ms-25">
                                        {{ config('app.name') }}
                                    </p>
                                </div>
                                <x-layout.language-switcher />
                            </div>
                            <h2 class="h1 fw-bolder mb-2">
                                {{ __('localization.auth_index_pass_recovery_code_by_phone_title') }}
                            </h2>
                            <p class="mb-2">
                                {{ __('localization.auth_index_pass_recovery_code_by_phone_instructions') }}
                            </p>
                        </div>

                        <form
                            id="reset-password-code-phone-form"
                            class="passRecovery-code-by-phone-form w-100 h-100 d-flex flex-column px-2"
                        >
                            <div class="mb-1">
                                <label class="form-label mb-1" for="passRecovery-by-phone">
                                    {{ __('localization.auth_index_pass_recovery_code_by_phone_confirmation_text') }}
                                    <span class="fw-medium-c" id="showWriteNumber">
                                        +38 (088) 888 88 88
                                    </span>
                                    {{ __('localization.auth_index_pass_recovery_code_by_phone_instructions') }}
                                </label>
                                <div
                                    id="otp-passRecovery-code-by-phone"
                                    data-formclassname=".passRecovery-code-by-phone-form"
                                    class="inputs d-flex justify-content-start"
                                >
                                    <input
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="first-byPhone-pass"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="second-byPhone-pass"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="mr-1 text-center form-control otp__input"
                                        type="number"
                                        id="third-byPhone-pass"
                                        maxlength="1"
                                    />
                                    <input
                                        disabled
                                        class="text-center form-control otp__input"
                                        type="number"
                                        id="fourth-byPhone-pass"
                                        maxlength="1"
                                    />
                                </div>
                                <div class="my-2 alert alert-danger d-none" role="alert">
                                    {{ __('localization.auth_index_pass_recovery_code_by_phone_error_message') }}
                                </div>
                            </div>

                            <a
                                id="refresh-password-phone-code"
                                href="#"
                                class="text-secondary text-decoration-underline"
                            >
                                <small>
                                    {{ __('localization.auth_index_pass_recovery_code_by_phone_resend_code') }}
                                </small>
                            </a>
                            <div class="h-100 w-100 d-flex justify-content-between align-items-end">
                                <button
                                    type="button"
                                    class="btn btn-to-passRecoveryPage"
                                    tabindex="4"
                                >
                                    <i
                                        data-feather="arrow-left"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                    <span class="align-middle d-sm-inline-block text-secondary">
                                        {{ __('localization.auth_index_pass_recovery_code_by_phone_back_button') }}
                                    </span>
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary disabled btnDisabled"
                                    tabindex="4"
                                >
                                    <span class="align-middle d-sm-inline-block">
                                        {{ __('localization.auth_index_pass_recovery_code_by_phone_next_button') }}
                                    </span>
                                    <i
                                        data-feather="arrow-right"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- фоновий контейнер  -->
                    <div
                        class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
                    >
                        <div class="px-4 py-3">
                            <img
                                class=""
                                src="{{ asset('assets/icons/entity/auth/auth_laptop_signup_code.svg') }}"
                                alt="logo"
                            />
                        </div>
                        <p>
                            {{ __('localization.auth_index_pass_recovery_code_by_phone_contact_admin') }}
                            <a
                                class="text-secondary text-decoration-underline"
                                data-bs-toggle="modal"
                                data-bs-target="#modalForSendSupport"
                                href="#"
                            >
                                {{ __('localization.auth_index_pass_recovery_code_by_phone_contact_admin_link') }}
                            </a>
                            {{ __('localization.auth_index_pass_recovery_code_by_phone_contact_solve_problem') }}
                        </p>
                        <p>
                            {{ __('localization.auth_index_pass_recovery_code_by_phone_remember_password') }}
                            <a href="#" class="fw-bold link-to-auth">
                                {{ __('localization.auth_index_pass_recovery_code_by_phone_login_link') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Третя сторінка відновлення паролю  -->
            <div class="h-100 d-none" id="passRecovery_page_writeNew">
                <div class="row h-100 add_shadow">
                    <!-- Контейнер з формою  -->
                    <div
                        class="main-cont col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 d-flex flex-column py-3"
                    >
                        <div class="px-2">
                            <div class="d-flex justify-content-between">
                                <div class="cont-logo d-flex mb-2">
                                    <img
                                        width="25px"
                                        src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                        alt="logo"
                                    />
                                    <p class="h5 fw-bolder my-auto ms-25">
                                        {{ config('app.name') }}
                                    </p>
                                </div>
                            </div>
                            <h2 class="h1 fw-bolder mb-2">
                                {{ __('localization.auth_index_pass_recovery_code_page_write_new_title') }}
                            </h2>
                            <p class="mb-2">
                                {{ __('localization.auth_index_pass_recovery_code_page_write_new_instructions') }}
                            </p>
                        </div>

                        <form
                            id="reset-new-password-form"
                            class="passRecovery-writeNew-form w-100 h-100 d-flex flex-column px-2"
                        >
                            <div class="mb-1">
                                <div class="">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="passRecovery-password">
                                            {{ __('localization.auth_index_pass_recovery_code_page_write_new_password_label') }}
                                        </label>
                                    </div>

                                    <div
                                        class="input-group input-group-merge form-password-toggle mb-1"
                                    >
                                        <input
                                            class="form-control form-control-merge"
                                            id="passRecovery-password"
                                            type="password"
                                            name="password"
                                            placeholder="{{ __('localization.auth_index_pass_recovery_code_page_write_new_password_placeholder') }}"
                                            minlength="8"
                                            aria-describedby="passRecovery-password"
                                            tabindex="2"
                                        />
                                        <span class="input-group-text cursor-pointer">
                                            <i data-feather="eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="d-flex justify-content-between">
                                        <label
                                            class="form-label"
                                            for="passRecovery-password-repeat"
                                        >
                                            {{ __('localization.auth_index_pass_recovery_code_page_write_new_confirm_password_label') }}
                                        </label>
                                    </div>
                                    <div class="container-confirmPass-and-alert">
                                        <div
                                            class="input-group input-group-merge form-password-toggle mb-1"
                                        >
                                            <input
                                                class="form-control form-control-merge"
                                                id="passRecovery-password-repeat"
                                                data-form="passRecovery-writeNew-form"
                                                type="password"
                                                minlength="8"
                                                name="confirmPassword"
                                                placeholder="{{ __('localization.auth_index_pass_recovery_code_page_write_new_confirm_password_placeholder') }}"
                                                aria-describedby="passRecovery-password-repeat"
                                                tabindex="2"
                                            />
                                            <span class="input-group-text cursor-pointer">
                                                <i data-feather="eye"></i>
                                            </span>
                                        </div>
                                        <div
                                            class="alert alert-danger alert-passRecovery-writeNew-form d-none"
                                            role="alert"
                                        >
                                            {{ __('localization.auth_index_pass_recovery_code_page_write_new_password_mismatch') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="h-100 w-100 d-flex justify-content-between align-items-end">
                                <button
                                    type="button"
                                    class="btn btn-to-passRecoveryPage-two"
                                    tabindex="4"
                                >
                                    <i
                                        data-feather="arrow-left"
                                        class="align-middle ms-sm-25 ms-0"
                                    ></i>
                                    <span class="align-middle d-sm-inline-block text-secondary">
                                        {{ __('localization.auth_index_pass_recovery_code_page_write_new_back_button') }}
                                    </span>
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary disabled btnDisabled"
                                    tabindex="4"
                                >
                                    <span class="align-middle d-sm-inline-block">
                                        {{ __('localization.auth_index_pass_recovery_code_page_write_new_submit_button') }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- фоновий контейнер  -->
                    <div
                        class="auth__imgContainer col-4 d-none d-sm-none d-md-none d-lg-flex col-sm-4 col-md-4 col-lg-4 col-xxl-4 d-flex flex-column justify-content-center p-3"
                    >
                        <div class="px-4 py-3">
                            <img
                                class=""
                                src="{{ asset('assets/icons/entity/auth/auth_laptop_tools.svg') }}"
                                alt="logo"
                            />
                        </div>
                        <p>
                            {{ __('localization.auth_index_pass_recovery_code_page_write_new_contact_admin') }}
                            <a
                                class="text-secondary text-decoration-underline"
                                data-bs-toggle="modal"
                                data-bs-target="#modalForSendSupport"
                                href="#"
                            >
                                {{ __('localization.auth_index_pass_recovery_code_page_write_new_contact_admin_link') }}
                            </a>
                            {{ __('localization.auth_index_pass_recovery_code_page_write_new_contact_solve_problem') }}
                        </p>
                        <p>
                            {{ __('localization.auth_index_pass_recovery_code_page_write_new_remember_password') }}
                            <a href="#" class="fw-bold link-to-auth">
                                {{ __('localization.auth_index_pass_recovery_code_page_write_new_login_link') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Модалка "зв'язатись з адміністратором" -->
    <x-modal.support-modal
        modalId="modalForSendSupport"
        titleKey="localization.auth_index_modal_for_send_support_title"
        subtitleKey="localization.auth_index_modal_for_send_support_subtitle"
        emailLabelKey="localization.auth_index_modal_for_send_support_email_label"
        emailPlaceholderKey="localization.auth_index_modal_for_send_support_email_placeholder"
        emailLinkKey="localization.auth_index_modal_for_send_support_email_link"
        phoneLabelKey="localization.auth_index_modal_for_send_support_phone_label"
        phonePlaceholderKey="localization.auth_index_modal_for_send_support_phone_placeholder"
        phoneLinkKey="localization.auth_index_modal_for_send_support_phone_link"
        contactInfoKey="localization.auth_index_modal_for_send_support_contact_info"
        phoneNumberKey="localization.auth_index_modal_for_send_support_phone_number"
        phoneNumberHref="tel:+38000000"
        phoneNumberDisplay="+38 (088) 888 88 88"
        emailAddressKey="localization.auth_index_modal_for_send_support_email_address"
        emailAddressHref="mailto:abc@example.com"
        emailAddressDisplay="example@email.com"
        cancelButtonKey="localization.auth_index_modal_for_send_support_cancel_button"
        sendButtonKey="localization.auth_index_modal_for_send_support_send_button"
    />

    <!-- Toast -->
    <div
        id="toastContainer"
        class="d-none position-fixed py-1 px-2 alert alert-success"
        role="alert"
        style="top: 30px; right: 50px"
    ></div>
@endsection
