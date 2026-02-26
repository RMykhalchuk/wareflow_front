@extends('layouts.admin')
@section('title', __('localization.container_create_title'))

@section('content')
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/containers',
                            'name' => __('localization.container_create_breadcrumbs_container'),
                        ],
                        [
                            'name' => __('localization.container_create_breadcrumbs_add_container'),
                        ],
                    ],
                ]
            )
        </x-slot>

        <x-slot:slot>
            <x-page-title :title="__('localization.container_create_breadcrumbs_add_container')" />

            <div id="container-create-root"></div>
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script src="{{ asset('js/react/containers-bundle.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.ContainerApp.mountCreate('container-create-root', {
                locale: '{{ app()->getLocale() }}',
                apiBaseUrl: '{{ config('app.url') }}',
                redirectUrl: '{{ route('containers.index') }}',
                cancelUrl: '{{ route('containers.index') }}',
            });
        });
    </script>
@endsection
