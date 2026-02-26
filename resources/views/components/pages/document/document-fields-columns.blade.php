@props([
    'fields',
    'documentData',
    'documentType',
])

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Lang;
@endphp

<div class="row">
    <div class="col-6">
        @foreach ($fields as $fieldKey => $fieldData)
            @if (array_key_exists($fieldKey, $documentData) && $loop->odd)
                @php
                    $translatedKey = Str::slug($fieldData['name'], '_');
                    $localizationKey = 'localization.documents.fields.' . $documentType->kind . '.label.' . $translatedKey;
                    $label = Lang::has($localizationKey) ? __($localizationKey) : $fieldData['name'];
                    $value = is_array($documentData[$fieldKey]) ? implode(' ', $documentData[$fieldKey]) : $documentData[$fieldKey];
                @endphp

                <div class="d-flex gap-1 mb-1">
                    <div class="f-15 col-6">{{ $label }}</div>
                    <div class="fw-6 col-6">{{ $value }}</div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="col-6">
        @foreach ($fields as $fieldKey => $fieldData)
            @if (array_key_exists($fieldKey, $documentData) && $loop->even)
                @php
                    $translatedKey = Str::slug($fieldData['name'], '_');
                    $localizationKey = 'localization.documents.fields.' . $documentType->kind . '.label.' . $translatedKey;
                    $label = Lang::has($localizationKey) ? __($localizationKey) : $fieldData['name'];
                    $value = is_array($documentData[$fieldKey]) ? implode(' ', $documentData[$fieldKey]) : $documentData[$fieldKey];
                @endphp

                <div class="d-flex gap-1 mb-1">
                    <div class="f-15 col-6">{{ $label }}</div>
                    <div class="fw-6 col-6">{{ $value }}</div>
                </div>
            @endif
        @endforeach
    </div>
</div>
