@props([
    'tag' => 'div', // 'div' або 'li'
    'class' => '',
])

<{{ $tag }} class="nav-item dropdown dropdown-language {{ $class }}">
    <a
        class="nav-link dropdown-toggle"
        href="#"
        data-bs-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
        id="dropdown-flag"
    >
        <i class="flag-icon"></i>
        <span class="selected-language" data-i18n=""></span>
    </a>

    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
        <a class="dropdown-item" href="#" data-language="uk">
            <i class="flag-icon flag-icon-ua"></i>
            <span data-i18n="LangTitleUA">
                {{ __('localization.nav.language.ua') }}
            </span>
        </a>

        <a class="dropdown-item" href="#" data-language="en">
            <i class="flag-icon flag-icon-us"></i>
            <span data-i18n="LangTitleEN">
                {{ __('localization.nav.language.en') }}
            </span>
        </a>

        {{-- <a class="dropdown-item" href="#" data-language="en"> --}}
        {{-- <i class="flag-icon flag-icon-de"></i> <span --}}
        {{-- data-i18n="LangTitleGE">{{ __('localization.nav_dropdown_language_de') }}</span> --}}
        {{-- </a> --}}
        {{-- <a class="dropdown-item" href="#" data-language="en"> --}}
        {{-- <i class="flag-icon flag-icon-pl"></i> <span --}}
        {{-- data-i18n="LangTitlePL">{{ __('localization.nav_dropdown_language_pl') }}</span> --}}
        {{-- </a> --}}
    </div>
</{{ $tag }}>
