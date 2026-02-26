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

        <!-- END: Vendor CSS-->
        @yield('before-style')
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset('vendors/css/forms/select/select2.min.css') }}"
        />
        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}" />
        <link rel="stylesheet" href="{{ asset(mix('css/base/themes/dark-layout.css')) }}" />
        <link rel="stylesheet" href="{{ asset(mix('css/base/themes/bordered-layout.css')) }}" />
        <link rel="stylesheet" href="{{ asset(mix('css/base/themes/semi-dark-layout.css')) }}" />

        <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}" />

        <link
            rel="stylesheet"
            href="{{ asset(mix('css/base/core/menu/menu-types/horizontal-menu.css')) }}"
        />

        <!-- laravel style -->
        <link rel="stylesheet" href="{{ asset(mix('css/overrides.css')) }}" />

        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

        @yield('page-style')
    </head>

    @php
        $currentLang = App\Http\Middleware\LocaleMiddleware::getLocale();
        $arrayLang = App\Http\Middleware\LocaleMiddleware::$languages;
        $languageTitles = \App\Http\Middleware\LocaleMiddleware::$languageTitles;
    @endphp

    <body
        class="horizontal-layout horizontal-menu navbar-floating footer-static"
        data-open="hover"
        data-menu="horizontal-menu"
        data-col=""
        data-framework="laravel"
        data-asset-path="{{ asset('/') }}"
    >
        <!-- BEGIN: Content-->
        <div class="content">
            {{-- Include Page Content --}}
            @yield('content')
        </div>
        <!-- End: Content-->

        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>

        <script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>

        <script src="{{ asset(mix('js/core/app.js')) }}"></script>

        @yield('table-js')

        <script src="{{ asset(mix('vendors/js/ui/jquery.sticky.js')) }}"></script>

        <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
        <script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>

        <script src="{{ asset(mix('js/core/scripts.js')) }}"></script>

        <script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
        <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>

        {{-- Це для іконок --}}
        <script src="{{ asset('assets/js/utils/icons/feather-icons-init.js') }}"></script>

        <script>
            if (!localStorage.getItem('Language')) {
                localStorage.setItem('Language', '{!! $currentLang !!}');
            }
        </script>

        <script src="{{ asset('assets/js/utils/localization/languageHandler.js') }}"></script>

        @yield('page-script')
    </body>
</html>
