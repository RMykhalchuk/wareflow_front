<div class="card-header">
    <h2 class="card-title text-dark fw-bolder">{{ __('localization.setting_nav_card_title') }}</h2>
</div>
<div class="card-body mt-1" id="setting-block">
    <div class="col-md-3 col-sm-12">
        <ul class="nav nav-pills flex-column" style="width: 208px">
            {{-- <li class="nav-item"> --}}
            {{-- <a class="nav-link {{ Request::is('general') ? 'active' : '' }} d-flex justify-content-start align-items-start" --}}
            {{-- id="stacked-pill-1" --}}
            {{-- {{ Request::is('general') ? 'data-bs-toggle=pill href=#vertical-pill-1' : 'href=/general' }} --}}
            {{-- aria-expanded="false"> --}}
            {{-- Загальне --}}
            {{-- </a> --}}
            {{-- </li> --}}

            <li class="nav-item">
                <a
                    class="nav-link {{ Str::contains(Request::path(), 'document-type') ? 'active' : '' }} d-flex justify-content-start align-items-start"
                    id="stacked-pill-2"
                    aria-expanded="false"
                    href="{{ route('document-type.index') }}"
                >
                    {{ __('localization.setting_nav_item_1') }}
                </a>
            </li>

            {{-- <li class="nav-item"> --}}
            {{-- <a --}}
            {{-- class="nav-link {{ Str::contains(Request::path(), 'type-goods') ? 'active' : '' }} d-flex justify-content-start align-items-start" --}}
            {{-- id="stacked-pill-3" --}}
            {{-- aria-expanded="false" --}}
            {{-- href="/type-goods" --}}
            {{-- > --}}
            {{-- {{ __('localization.setting_nav_item_2') }} --}}
            {{-- </a> --}}
            {{-- </li> --}}

            {{-- <li class="nav-item"> --}}
            {{-- <a class="nav-link {{ Request::is('type-container') ? 'active' : '' }} d-flex justify-content-start align-items-start" --}}
            {{-- id="stacked-pill-3" --}}
            {{-- {{ Request::is('type-container') ? 'data-bs-toggle=pill href=#vertical-pill-3' : 'href=/type-container' }} --}}
            {{-- aria-expanded="false"> --}}
            {{-- Типи контейнерів --}}
            {{-- </a> --}}
            {{-- </li> --}}

            <li class="nav-item">
                <a
                    class="nav-link {{ Str::contains(Request::path(), 'type-categories') ? 'active' : '' }} d-flex justify-content-start align-items-start"
                    id="stacked-pill-5"
                    aria-expanded="false"
                    href="{{ route('type-categories.index') }}"
                >
                    {{ __('localization.setting_nav_item_2') }}
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ Str::contains(Request::path(), 'workspaces/' . Auth::user()->current_workspace_id . '/edit') ? 'active' : '' }} d-flex justify-content-start align-items-start"
                    id="stacked-pill-4"
                    aria-expanded="false"
                    href="{{ route('workspaces.edit', ['workspace' => Auth::user()->current_workspace_id]) }}"
                >
                    {{ __('localization.setting_nav_item_3') }}
                </a>
            </li>

            {{-- <li class="nav-item"> --}}
            {{-- <a --}}
            {{-- class="nav-link {{ Str::contains(Request::path(), 'regulations') ? 'active' : '' }} d-flex justify-content-start align-items-start" --}}
            {{-- id="stacked-pill-5" --}}
            {{-- aria-expanded="false" --}}
            {{-- href="/regulations" --}}
            {{-- > --}}
            {{-- {{ __('localization.setting_nav_item_4') }} --}}
            {{-- </a> --}}
            {{-- </li> --}}

            {{-- <li class="nav-item"> --}}
            {{-- <a --}}
            {{-- class="nav-link {{ Str::contains(Request::path(), 'residue-control/create') ? 'active' : '' }} d-flex justify-content-start align-items-start" --}}
            {{-- id="stacked-pill-6" --}}
            {{-- aria-expanded="false" --}}
            {{-- href="/residue-control/create" --}}
            {{-- > --}}
            {{-- {{ __('localization.setting_nav_item_5') }} --}}
            {{-- </a> --}}
            {{-- </li> --}}
        </ul>
    </div>
</div>
