<!DOCTYPE html>
<html lang="{{ app()->getLocale() == 'ua' ? 'uk' : app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta
            name="viewport"
            content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui"
        />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>@yield('title')</title>
        <link rel="apple-touch-icon" href="{{ asset('images/ico/favicon-32x32.png') }}" />
        <link
            rel="shortcut icon"
            type="image/x-icon"
            href="{{ asset('images/logo/favicon.ico') }}"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
            rel="stylesheet"
        />

        {{-- Include core + vendor Styles --}}
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}" />
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}"
        />

        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/css/intlTelInput.css"
        />
        <link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}" />
        <link rel="stylesheet" href="{{ asset(mix('css/base/themes/dark-layout.css')) }}" />
        <link rel="stylesheet" href="{{ asset(mix('css/base/themes/bordered-layout.css')) }}" />
        <link rel="stylesheet" href="{{ asset(mix('css/base/themes/semi-dark-layout.css')) }}" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    </head>

    @php
        $currentLang = App\Http\Middleware\LocaleMiddleware::getLocale();
        $arrayLang = App\Http\Middleware\LocaleMiddleware::$languages;
        $languageTitles = \App\Http\Middleware\LocaleMiddleware::$languageTitles;
    @endphp

    <body data-framework="laravel" style="background: #ffffff">
        <!-- (загальний контейнер) -->
        <div class="h-100 d-flex align-items-center justify-content-center">
            @yield('content')
        </div>
    </body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/js/intlTelInput.min.js"></script>

    <script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/ui/jquery.sticky.js')) }}"></script>

    <script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
    <script src="{{ asset(mix('js/core/app.js')) }}"></script>

    <script src="{{ asset(mix('js/core/scripts.js')) }}"></script>

    <script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>

    <script src="{{ asset('vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>

    <script src="{{ asset('js/scripts/forms/form-wizard.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/auth/auth.js') }}"></script>

    <script type="module">
        import { inputSelectCountry } from '{{ asset('assets/js/utils/inputSelectCountry.js') }}';

        inputSelectCountry('#authNumberInp');
        inputSelectCountry('#registerNumberInp');
        inputSelectCountry('#passRecoveryNumberInp');
        inputSelectCountry('#feedBackNumberInp');
    </script>

    <script src="{{ asset('assets/js/utils/icons/feather-icons-init.js') }}"></script>

    {{-- todo remove --}}
    <script>
        $('.cancel-btn').on('click', function () {
            $('.modal').modal('hide');
        });
    </script>

    <script>
        if (!localStorage.getItem('Language')) {
            localStorage.setItem('Language', '{!! $currentLang !!}');
        }
    </script>

    <script src="{{ asset('assets/js/utils/localization/languageHandler.js') }}"></script>
</html>
