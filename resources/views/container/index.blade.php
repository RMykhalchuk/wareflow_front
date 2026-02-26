@extends('layouts.admin')
@section('title', __('localization.container_index_title'))

@section('before-style')
    <style>
        .gap-50 {
            gap: 0.5rem;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .container-list-widget {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">{{ __('localization.container_index_container') }}</h4>
            <a href="{{ route('containers.create') }}" class="btn btn-primary">
                <i data-feather="plus" class="me-50"></i>
                {{ __('localization.container_index_add_container') }}
            </a>
        </div>
        <div class="card-body">
            <div id="container-app"></div>
        </div>
    </div>
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
