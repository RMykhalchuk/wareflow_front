@switch($documentType->key)
    @case('ttn')
        <script
            type="module"
            src="{{ asset("assets/js/modules/document/$folder/ttn.js") }}"
        ></script>

        @break
@endswitch
