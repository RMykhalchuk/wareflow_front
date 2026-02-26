@extends('layouts.empty')

@section('title', __('localization.errors_index_title'))

@section('page-style')
    
@endsection

@section('before-style')
    
@endsection

@section('content')
    {{-- todo не локалізовується( --}}

    <div class="container d-flex justify-content-center">
        <div
            class="d-flex align-items-center align-content-center row mx-0"
            style="width: 1150px; height: 100vh"
        >
            <div class="col-12 col-md-12 col-lg-6">
                <h1 class="fw-bolder">{{ __('localization.errors_index_title_content') }}</h1>
                <p>{!! __('localization.errors_index_message') !!}</p>
                <div class="">
                    <a
                        style="width: 443px"
                        class="btn btn-primary d-flex align-items-center fw-bold gap-50 justify-content-center"
                        href="{{ url()->previous() }}"
                    >
                        <img
                            src="{{ asset('assets/icons/entity/errors/arrow-narrow-left.svg') }}"
                            alt="{{ __('localization.errors_index_arrow_alt') }}"
                        />
                        {{ __('localization.errors_index_button') }}
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-6 d-flex justify-content-center">
                <img
                    width="100%"
                    src="{{ asset('assets/icons/entity/errors/404.svg') }}"
                    alt="{{ __('localization.errors_index_404_alt') }}"
                />
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    
@endsection
