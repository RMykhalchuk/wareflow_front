@extends('layouts.admin')
@section('title', __('localization.container_index_title'))

@section('before-style')
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
@endsection

@section('content')
    <div id="container-app"></div>
@endsection

@section('page-script')
    <script src="{{ asset('js/react/containers-bundle.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.ContainerApp) {
                window.ContainerApp.mount('container-app', {
                    locale: '{{ app()->getLocale() }}',
                    apiBaseUrl: '{{ url('/') }}'
                });
            }

            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection
