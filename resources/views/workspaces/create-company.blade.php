@extends('layouts.empty')

@section('title', __('localization.workspaces_create_company_title'))

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}"
    />

    <!-- <link rel="stylesheet" type="text/css" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"> -->

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/css/intlTelInput.css"
    />
@endsection

@section('before-style')
    
@endsection

@section('content')
    @include('onboarding.base-steps-onboarding', ['isShowBtnStep1' => 'd-none'])
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/js/intlTelInput.min.js"></script>

    <script type="module">
        import { inputSelectCountry } from '{{ asset('assets/js/utils/inputSelectCountry.js') }}';

        inputSelectCountry('#feedBackNumberInp');
        inputSelectCountry('#phone');
    </script>

    <script type="module">
        import { selectDictionaries } from '{{ asset('assets/js/utils/selectDictionaries.js') }}';

        selectDictionaries();
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>

    <!-- <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script> -->
    <!-- <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script> -->

    <script src="{{ asset('vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-input-mask.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/onboarding/onboarding.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/entity/company/company.js') }}"></script>
    <script src="{{ asset('assets/js/utils/validationInputs.js') }}"></script>
@endsection
