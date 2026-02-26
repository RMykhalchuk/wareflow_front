@php
    use App\Models\User;

    //Todo винести логіку в Service + ServiceProvider . P.S ця херотень є ще в  доках та тасках у index
    $user = \Auth::user()->load([
        "workingData.warehouses",
        "workingData.currentWarehouse",
        "current_workspace",
    ]);

    $wd = $user->workingDataByGuard;
    $warehouses = $wd?->warehouses ?? collect();
    $disabled = $warehouses->isEmpty();
    $currentWarehouseId = $wd?->current_warehouse_id;
    $currentWorkspaceName = $user->current_workspace?->name ?? "";
@endphp

<nav
    class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center border border-bottom-2 navbar-brand-left"
    data-nav="brand-left"
    style="z-index: 10000 !important"
>
    <div class="navbar-header d-xl-block d-none">
        <ul class="nav navbar-nav" style="margin-left: 28px">
            <li class="nav-item row">
                <div class="d-flex align-items-center gap-1">
                    <div class="align-self-center">
                        <a class="navbar-brand me-0 d-flex" href="{{ route("main-page") }}">
                            <img
                                width="25px"
                                src="{{ asset("assets/icons/entity/logo/logo-consolid.svg") }}"
                                alt="nav-logo"
                            />
                            <p class="h5 fw-bolder my-auto ms-25">{{ config("app.name") }}</p>
                        </a>
                    </div>
                    <div class="workspace-profile" style="white-space: nowrap">
                        <div class="text-dark text-center fw-bolder">
                            {{ $currentWorkspaceName }}
                        </div>
                    </div>
                    <div class="width-200">
                        <x-warehouse-select-component
                            :disabled="$disabled"
                            :warehouses="$warehouses"
                            :current-warehouse-id="$currentWarehouseId"
                        />
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="d-xl-none mb-0 ps-1">
                <li id="open-mobile-menu-js" class="list-s-none">
                    <a class="nav-link menu-toggle nav-link-burger-c" href="#">
                        <i class="ficon" data-feather="menu"></i>
                    </a>
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            {{-- id="dropdown-flag" на a все ламає --}}

            <x-layout.language-switcher tag="li" />

            {{-- TODO SWITCH THEME --}}
            {{-- <x-layout.navbar.theme-switcher /> --}}

            <li class="nav-item nav-search bookmarks-btn px-50">
                <a
                    id="offCanvasToggleLink"
                    class="p-0 nav-link nav-link-grid"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasEnd"
                    aria-controls="offcanvasEnd"
                >
                    <img
                        class="nav-img-bookmarks"
                        src="{{ asset("assets/icons/entity/navbar/bookmarks.svg") }}"
                        alt="bookmarks"
                    />
                </a>
            </li>

            {{-- <x-layout.navbar.notification-dropdown /> --}}

            <li class="nav-item dropdown dropdown-user" style="z-index: 999">
                <a
                    class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                >
                    <span class="avatar">
                        <img
                            class="round"
                            src="{{ Auth::user()->getAvatar() }}"
                            alt="avatar"
                            height="40"
                            width="40"
                        />
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="dropdown-user">
                    <h6 class="dropdown-header">
                        {{ __("localization.nav.profile.manage_profile") }}
                    </h6>
                    <div class="dropdown-divider"></div>
                    <a
                        class="dropdown-item"
                        href="{{ route("user.show", ["user" => auth()->id()]) }}"
                    >
                        <i class="me-50" data-feather="user"></i>
                        {{ __("localization.nav.profile.profile") }}
                    </a>

                    @can("view-dictionaries")
                        <a class="dropdown-item" href="{{ route("document-type.index") }}">
                            <i class="me-50" data-feather="settings"></i>
                            {{ __("localization.nav.profile.settings") }}
                        </a>
                    @endcan

                    @if (Auth::User())
                        <div class="dropdown-divider"></div>
                    @endif

                    @if (Auth::check())
                        <a
                            class="dropdown-item"
                            href="{{ route("logout") }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        >
                            <i class="me-50" data-feather="power"></i>
                            {{ __("localization.nav.profile.logout") }}
                        </a>
                        <form method="POST" id="logout-form" action="{{ route("logout") }}">
                            @csrf
                        </form>
                    @else
                        <form method="POST" action="{{ route("logout") }}">
                            @csrf
                            <button type="submit" class="btn-link dropdown-item">
                                <i class="me-50" data-feather="log-out"></i>
                                {{ __("localization.nav.profile.logout") }}
                            </button>
                        </form>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- BEGIN: Main Menu-->
<div class="horizontal-menu-wrapper" style="z-index: 9999">
    <div
        class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-light navbar-shadow menu-border nav-width-100"
        role="navigation"
        data-menu="menu-wrapper"
        data-menu-type="floating-nav"
        style="margin: 0; width: 295px; z-index: 10001"
    >
        <div class="navbar-header px-2 h-auto" style="width: 295px">
            <ul class="nav navbar-nav ms-50">
                <li class="nav-item d-flex flex-row justify-content-between">
                    <div class="nav-item me-auto">
                        <a class="navbar-brand" href="{{ route("main-page") }}">
                            <span class="brand-logo">
                                <img
                                    width="25px"
                                    src="{{ asset("assets/icons/entity/logo/logo-consolid.svg") }}"
                                    alt="nav-logo"
                                />
                            </span>
                            <h2 class="brand-text mb-0" style="color: #5e5873">
                                {{ config("app.name") }}
                            </h2>
                        </a>
                    </div>
                    <div class="nav-item nav-toggle">
                        <a
                            class="nav-link modern-nav-toggle pe-0"
                            style="margin-top: 26px; margin-bottom: 0"
                            data-bs-toggle="collapse"
                        >
                            <i
                                class="d-block d-xl-none text-dark toggle-icon font-medium-4"
                                data-feather="x"
                            ></i>
                        </a>
                    </div>
                </li>
            </ul>

            <div class="workspace-profile mt-1 p-1">
                <span class="text-dark fw-bolder">{{ $currentWorkspaceName }}</span>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <!-- Horizontal menu content-->
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <!-- include ../../../includes/mixins-->
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                {{-- <li class=" nav-item"> --}}
                {{-- <a class="nav-link d-flex align-items-center custom-padding" href="/analytic"> --}}
                {{-- <img class="type-svg" src="{{ asset("assets/icons/chart-pie.svg") }}" alt="chart-pie"> --}}
                {{-- <span class="nav-title" data-i18n="Analytic">Аналітика</span> --}}
                {{-- </a> --}}
                {{-- </li> --}}

                @can("view-dictionaries")
                    {{-- Довідники --}}
                    <li
                        class="dropdown nav-item"
                        data-menu="dropdown"
                        onclick="toggleClassOpen(this)"
                    >
                        <a
                            class="dropdown-toggle nav-link d-flex align-items-center custom-padding"
                            href="#"
                            data-bs-toggle="dropdown"
                        >
                            <img
                                class="type-svg"
                                src="{{ asset("assets/icons/entity/navbar/cha.svg") }}"
                                alt="cha"
                            />
                            <span class="nav-title" data-i18n="Reference books">
                                {{ __("localization.nav.directories.title") }}
                            </span>
                        </a>

                        <ul class="dropdown-menu" data-bs-popper="none">
                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("main-page") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Users">
                                        {{ __("localization.nav.directories.users") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("companies.index") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />

                                    <span class="fw-normal" data-i18n="Company">
                                        {{ __("localization.nav.directories.companies") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("locations.index") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Location">
                                        {{ __("localization.nav.directories.locations") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("warehouses.index") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Location">
                                        {{ __("localization.nav.directories.warehouses") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("warehouse-erp.index") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Location">
                                        {{ __("localization.nav.directories.warehouses_erp") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center disabled"
                                    {{-- class="dropdown-item d-flex align-items-center" --}}
                                    href="#"
                                    {{-- href="{{ route("transports.index") }}" --}}
                                    onclick="return false;"
                                    style="pointer-events: none; opacity: 0.5"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Transport">
                                        {{ __("localization.nav.directories.vehicle") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center disabled"
                                    {{-- class="dropdown-item d-flex align-items-center" --}}
                                    href="#"
                                    {{-- href="{{ route("transport-equipments.index") }}" --}}
                                    onclick="return false;"
                                    style="pointer-events: none; opacity: 0.5"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="TransportEquipment">
                                        {{ __("localization.nav.directories.add_equipment") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("sku.index") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Goods">
                                        {{ __("localization.nav.directories.goods") }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("containers.index") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Container">
                                        {{ __("localization.nav.directories.tare") }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Складська логістика --}}
                <li class="dropdown nav-item" data-menu="dropdown" onclick="toggleClassOpen(this)">
                    <a
                        class="dropdown-toggle nav-link d-flex align-items-center custom-padding"
                        href="#"
                        data-bs-toggle="dropdown"
                    >
                        <img
                            class="type-svg"
                            src="{{ asset("assets/icons/entity/navbar/building.svg") }}"
                            alt="building"
                        />
                        <span class="nav-title" data-i18n="Warehouse logistic">
                            {{ __("localization.nav.logistics.warehouse_logistic") }}
                        </span>
                    </a>
                    <ul class="dropdown-menu" data-bs-popper="none">
                        <li>
                            <a
                                class="dropdown-item d-flex align-items-center"
                                href="{{ route("leftovers.index") }}"
                            >
                                <x-icon.nav-circle class="me-1" />
                                <span class="fw-normal" data-i18n="Leftovers">
                                    {{ __("localization.nav.inventory.leftovers") }}
                                </span>
                            </a>
                        </li>
                        @can("view-dictionaries")
                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="{{ route("leftovers-erp.index") }}"
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal" data-i18n="Leftovers-erp">
                                        {{ __("localization.nav.inventory.leftovers-erp") }}
                                    </span>
                                </a>
                            </li>
                        @endcan

                        <li>
                            <a
                                class="dropdown-item d-flex align-items-center"
                                href="{{ route("container-register.index") }}"
                            >
                                <x-icon.nav-circle class="me-1" />
                                <span class="fw-normal" data-i18n="containers-register">
                                    {{ __("localization.nav.registers.containers") }}
                                </span>
                            </a>
                        </li>
                        {{-- Якщо потрібно --}}
                        {{--
                            <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('residue-control.index') }}">
                            <x-icon.nav-circle class="me-1" />
                            <span class="fw-normal" data-i18n="Inventory Control">{{ __('localization.nav.inventory.inventory_control') }}</span>
                            </a>
                            </li>
                        --}}
                    </ul>
                </li>

                {{-- Документи --}}
                <li class="dropdown nav-item" data-menu="dropdown" onclick="toggleClassOpen(this)">
                    <a
                        class="dropdown-toggle nav-link d-flex align-items-center custom-padding"
                        href="#"
                        data-bs-toggle="dropdown"
                    >
                        <img
                            class="type-svg"
                            src="{{ asset("assets/icons/entity/navbar/book-2.svg") }}"
                            alt="book-2"
                        />
                        <span class="nav-title" data-i18n="Documents">
                            {{ __("localization.nav.documents.title") }}
                        </span>
                    </a>
                    <ul class="dropdown-menu" data-bs-popper="none">
                        <li>
                            <a
                                class="dropdown-item d-flex align-items-center"
                                href="{{ route("document.index") }}"
                            >
                                <img
                                    class="type-svg me-2"
                                    src="{{ asset("assets/icons/entity/navbar/book-2.svg") }}"
                                    alt="book-2"
                                />
                                <span class="fw-normal" data-i18n="All documents">
                                    {{ __("localization.nav.documents.all_documents") }}
                                </span>
                            </a>
                        </li>

                        @foreach ($doctypes as $doctype)
                            @if ($loop->iteration > 12)
                                @break
                            @endif

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center {{ $disabled ? "disabled text-muted" : "" }}"
                                    @if (! $disabled)
                                        href="{{ route("document.table", ["document_type" => $doctype->id, "warehouse_id" => $currentWarehouseId]) }}"
                                    @else
                                        tabindex="-1"
                                        aria-disabled="true"
                                    @endif
                                    {{-- href="{{ route('document.table', ['document_type' => $doctype->id]) }}" --}}
                                    {{-- Якщо вам потрібно додати $currentWarehouseId до URL, ви можете зробити це так: --}}
                                >
                                    <x-icon.nav-circle class="me-1" />
                                    <span class="fw-normal titles-doctypes">
                                        {{ $doctype->name }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                {{-- Інвертизація --}}
                <li class="dropdown nav-item" data-menu="dropdown" onclick="toggleClassOpen(this)">
                    <a
                        class="dropdown-toggle nav-link d-flex align-items-center custom-padding"
                        href="#"
                        data-bs-toggle="dropdown"
                    >
                        <i data-feather="list"></i>

                        <span class="nav-title" data-i18n="Register">
                            {{ __("localization.nav.inventory.inventory") }}
                        </span>
                    </a>
                    <ul class="dropdown-menu" data-bs-popper="none">
                        <li>
                            <a
                                class="dropdown-item d-flex align-items-center"
                                href="{{ route("inventory.index") }}"
                            >
                                <x-icon.nav-circle class="me-1" />
                                <span class="fw-normal" data-i18n="inventory-task">
                                    {{ __("localization.nav.inventory.inventory_task") }}
                                </span>
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item d-flex align-items-center"
                                href="{{ route("inventory.manual") }}"
                            >
                                <x-icon.nav-circle class="me-1" />
                                <span class="fw-normal" data-i18n="inventory-manual">
                                    {{ __("localization.nav.inventory.inventory_manual") }}
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Таски --}}
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center custom-padding"
                        href="{{ route("tasks.index") }}"
                    >
                        <i data-feather="feather"></i>
                        <span class="nav-title" data-i18n="Register">
                            {{ __("localization.nav.tasks.tasks") }}
                        </span>
                    </a>
                </li>

                {{-- Реєстри --}}
                {{-- <li class="dropdown nav-item" data-menu="dropdown" onclick="toggleClassOpen(this)"> --}}
                {{-- <a --}}
                {{-- class="dropdown-toggle nav-link d-flex align-items-center custom-padding" --}}
                {{-- href="#" --}}
                {{-- data-bs-toggle="dropdown" --}}
                {{-- > --}}
                {{-- <img --}}
                {{-- class="type-svg" --}}
                {{-- src="{{ asset('assets/icons/entity/navbar/book-2.svg') }}" --}}
                {{-- alt="book-2" --}}
                {{-- /> --}}
                {{-- <span class="nav-title" data-i18n="Register"> --}}
                {{-- {{ __('localization.nav.registers.title') }} --}}
                {{-- </span> --}}
                {{-- </a> --}}
                {{-- <ul class="dropdown-menu" data-bs-popper="none"> --}}
                {{-- <li> --}}
                {{-- <a --}}
                {{-- class="dropdown-item d-flex align-items-center" --}}
                {{-- href="{{ route('register.storekeeper') }}" --}}
                {{-- > --}}
                {{-- <x-icon.nav-circle class="me-1" /> --}}
                {{-- <span class="fw-normal" data-i18n="Storekeeper"> --}}
                {{-- {{ __('localization.nav.registers.storekeepers') }} --}}
                {{-- </span> --}}
                {{-- </a> --}}
                {{-- </li> --}}
                {{-- <li> --}}
                {{-- <a --}}
                {{-- class="dropdown-item d-flex align-items-center" --}}
                {{-- href="{{ route('register.guardian') }}" --}}
                {{-- > --}}
                {{-- <x-icon.nav-circle class="me-1" /> --}}
                {{-- <span class="fw-normal" data-i18n="Guard"> --}}
                {{-- {{ __('localization.nav.registers.guard') }} --}}
                {{-- </span> --}}
                {{-- </a> --}}
                {{-- </li> --}}
                {{-- </ul> --}}
                {{-- </li> --}}
            </ul>
        </div>
    </div>
</div>
<!-- END: Main Menu-->
