@extends('layouts.admin')
@section('title', __('localization.registers_create_title'))

@section('content')
    <x-layout.container>
        <x-slot:slot>
            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.registers_create_title_table') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <form method="post" action="{{ route('register.store') }}">
                        @csrf
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label">
                                {{ __('localization.registers_create_auto_name_label') }}
                            </label>
                            <input type="text" class="form-control" name="auto_name" />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label">
                                {{ __('localization.registers_create_time_label') }}
                            </label>
                            <input type="text" class="form-control" name="arrive" />
                        </div>
                        <input
                            type="submit"
                            class="btn btn-primary"
                            value="{{ __('localization.registers_create_submit_button') }}"
                        />
                    </form>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>
@endsection
