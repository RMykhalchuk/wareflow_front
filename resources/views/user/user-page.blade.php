@extends('layouts.admin')
@section('title', __('localization.user.view.profile_title'))

@section('page-style')
    
@endsection

@section('before-style')
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.base.css') }}"
        type="text/css"
    />
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css') }}"
        type="text/css"
    />
@endsection

@section('content')
    <x-layout.container fluid>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/',
                            'name' => __('localization.user.view.users'),
                        ],
                        [
                            'name' => __('localization.user.view.view'),
                            'name2' => $user->surname . ' ' . $user->name . ' ' . $user->patronymic,
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-ui.icon-link-button
                    href="{{ route('user.update', ['user' => $user->id]) }}"
                    :title="__('localization.container_view_edit')"
                    icon="edit"
                />

                <x-ui.icon-dropdown id="header-dropdown">
                    <form
                        method="POST"
                        class="text-center"
                        action="{{ route('user.delete', ['user' => $user->id]) }}"
                    >
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn text-danger">
                            {{ __('localization.user.view.deactivate') }}
                        </button>
                    </form>
                </x-ui.icon-dropdown>
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested>
                <x-slot:header>
                    <div
                        class="col-12 d-flex flex-row px-1 justify-content-start text-center gap-1"
                    >
                        <div class="d-flex flex-column gap-1">
                            <img
                                src="{{ $user->getAvatar() }}"
                                id="account-upload-img"
                                class="uploadedAvatar rounded"
                                alt="profile image"
                                height="80"
                                width="80"
                            />
                            <div class="d-block d-md-none">
                                @if ($user->isOnline())
                                    <span class="badge badge-light-success">
                                        {{ __('localization.user.view.online') }}
                                    </span>
                                @else
                                    <span class="badge badge-light-danger">
                                        {{ __('localization.user.view.offline') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div
                            class="d-flex flex-column justify-content-between justify-content-md-around"
                        >
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="mb-0 fw-bolder text-start">
                                    {{ $user->surname ?? '' }} {{ $user->name ?? '' }}
                                    {{ $user->patronymic ?? '' }}
                                </h3>
                                <div class="d-none d-md-block">
                                    @if ($user->isOnline())
                                        <span class="badge badge-light-success">
                                            {{ __('localization.user.view.online') }}
                                        </span>
                                    @else
                                        <span class="badge badge-light-danger">
                                            {{ __('localization.user.view.offline') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-start">
                                <div class="fw-bold">
                                    @switch($user->workingData->position->key ?? null)
                                        @case('palet')
                                            {{ __('localization.user.view.view_position_palet') }}

                                            @break
                                        @case('complect1')
                                            {{ __('localization.user.view.view_position_complect1') }}

                                            @break
                                        @case('complect2')
                                            {{ __('localization.user.view.view_position_complect2') }}

                                            @break
                                        @case('complect3')
                                            {{ __('localization.user.view.view_position_complect3') }}

                                            @break
                                        @case('complect4')
                                            {{ __('localization.user.view.view_position_complect4') }}

                                            @break
                                        @case('complect5')
                                            {{ __('localization.user.view.view_position_complect5') }}

                                            @break
                                        @case('driver')
                                            {{ __('localization.user.view.view_position_driver') }}

                                            @break
                                        @case('logist')
                                            {{ __('localization.user.view.view_position_logist') }}

                                            @break
                                        @case('dispatcher')
                                            {{ __('localization.user.view.view_position_dispatcher') }}

                                            @break
                                        @default
                                            {{ __('localization.user.view.no_position') }}
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-slot:body>
                    <x-ui.section-divider />

                    <x-ui.col-row-wrapper>
                        <x-ui.section-card-title level="5">
                            {{ __('localization.user.view.personal_data') }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            <x-card.card-data-row
                                :label="__('localization.user.view.birthday')"
                                :value="$user->birthday ?? __('localization.user.view.no_data')"
                            />

                            <x-card.card-data-row
                                :label="__('localization.user.view.email')"
                                :value="$user->email"
                            />

                            <x-card.card-data-row
                                :label="__('localization.user.view.phone')"
                                :value="$user->phone"
                            />

                            <x-card.card-data-row
                                :label="__('localization.user.view.gender')"
                                :value="$user->sex ? __('localization.user.view.female') : __('localization.user.view.male')"
                            />

                            <x-card.card-data-row
                                :label="__('localization.user.view.permit')"
                                :value="'user-id/'.$user->id"
                            />
                        </x-card.card-data-wrapper>

                        <x-ui.section-card-title level="5">
                            {{ __('localization.user.view.working_data') }}
                        </x-ui.section-card-title>

                        <x-card.card-data-wrapper>
                            {{-- Компанія --}}
                            <x-card.card-data-row :label="__('localization.user.view.company')">
                                <a
                                    class="link-secondary text-decoration-underline"
                                    href="{{ route('companies.show', ['company' => $user->workingData->company->id]) }}"
                                >
                                    {{ $user->workingData->company->type->key === 'legal' ? $user->workingData->company->company->name : $user->workingData->company->company->surname . ' ' . $user->workingData->company->company->first_name }}
                                </a>
                            </x-card.card-data-row>

                            {{-- Роль --}}
                            <x-card.card-data-row
                                :label="__('localization.user.view.role')"
                                :value="match($user->workingData->role[0]->name) {
                                    'super_admin' => __('localization.user.view.role_system_administrator'),
                                    'admin' => __('localization.user.view.role_administrator'),
                                    'user' => __('localization.user.view.role_user'),
                                    'logistic' => __('localization.user.view.role_logistics'),
                                    'dispatcher' => __('localization.user.view.role_dispatcher'),
                                    default => data_get($user, 'workingData.role.0.title'),
                                }"
                            />

                            {{-- Водій --}}
                            @if ($user->workingData->position?->key === 'driver')
                                <x-card.card-data-row
                                    :label="__('localization.user.view.driver_license_number')"
                                    :value="sprintf('№ %s', $user->workingData->driving_license_number ?? __('localization.user.view.no_data'))"
                                />

                                <x-card.card-data-row
                                    :label="__('localization.user.view.driver_license_expiry')"
                                    :value="sprintf('%s %s', __('localization.user.view.expires'), $user->workingData->driver_license_date ?? __('localization.user.view.no_data'))"
                                />

                                <x-card.card-data-row
                                    :label="__('localization.user.view.driver_license')"
                                >
                                    <div class="d-flex align-items-center gap-1">
                                        <img
                                            src="{{ asset('assets/icons/entity/user/file-upload.svg') }}"
                                            alt="file-upload"
                                        />
                                        <a
                                            class="link-secondary text-truncate text-decoration-underline"
                                            download="{{ $drivingLicenseFile->name }}"
                                            href="{{ '/files/uploads/driver/driving_license/' . $user->id . '.' . $user->workingData->driving_license_doctype }}"
                                        >
                                            {{ $drivingLicenseFile->name }}
                                        </a>
                                    </div>
                                </x-card.card-data-row>

                                <x-card.card-data-row
                                    :label="__('localization.user.view.health_book_number')"
                                    :value="sprintf('№ %s', $user->workingData->health_book_number ?? __('localization.user.view.no_data'))"
                                />

                                <x-card.card-data-row
                                    :label="__('localization.user.view.health_book_expiry')"
                                    :value="sprintf('%s %s', __('localization.user.view.expires'), $user->workingData->health_book_date ?? __('localization.user.view.no_data'))"
                                />

                                @if ($healthBookFile?->name)
                                    <x-card.card-data-row
                                        :label="__('localization.user.view.health_book')"
                                    >
                                        <div class="d-flex align-items-center gap-1">
                                            <img
                                                src="{{ asset('assets/icons/entity/user/file-upload.svg') }}"
                                                alt="file-upload"
                                            />
                                            <a
                                                class="link-secondary text-truncate text-decoration-underline"
                                                download="{{ $healthBookFile->name }}"
                                                href="{{ '/files/uploads/driver/health_book/' . $user->id . '.' . $user->health_book_doctype }}"
                                            >
                                                {{ $healthBookFile->name }}
                                            </a>
                                        </div>
                                    </x-card.card-data-row>
                                @endif
                            @endif

                            <x-card.card-data-row :label="__('localization.user.view.warehouses')">
                                @php
                                    $warehouseNames = optional($user->workingData?->warehouses)->pluck('name');
                                @endphp

                                @if ($warehouseNames && $warehouseNames->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-50">
                                        @foreach ($warehouseNames as $name)
                                            <span class="badge badge-light-primary">
                                                {{ $name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="d-flex flex-wrap gap-50">
                                        <span class="badge badge-light-primary">
                                            {{ __('localization.user.view.all') }}
                                        </span>
                                    </div>
                                @endif
                            </x-card.card-data-row>
                        </x-card.card-data-wrapper>
                    </x-ui.col-row-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script type="module">
        import { copyGroup } from '{{ asset('assets/js/utils/copy-group.js') }}';

        copyGroup(); // За замовчуванням

        // або з кастомними селекторами / шаблоном:
        // setupCopyGroup('.my-copy-btn', {
        //     getContentId: (btnId) => btnId.replace('copy-btn-', 'content-'),
        //     onCopied: (button, contentElement, value) => {
        //         console.log(`Скопійовано: ${value}`);
        //     }
        // });
    </script>
@endsection
