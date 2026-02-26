@props([
    'id',
    'titleKey',
    'checkboxId' => null,
    'defaultFields' => [],
    'fields' => [],
    'mode' => 'create',
])
{{-- // масив додаткових полів із settings --}}
{{-- // create | edit --}}
<div>
    <div class="accordion-field-header d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <h4 class="m-0 fw-bolder">
                {{ __($titleKey) }}
            </h4>
        </div>

        @if ($checkboxId)
            <div class="form-check form-switch">
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="{{ $checkboxId }}"
                    {{-- у create завжди checked, в edit — якщо є хоч дефолтні чи збережені --}}
                    @if(count($defaultFields) || count($fields)) checked @endif
                />
            </div>
        @endif
    </div>

    {{-- Default fields – завжди показуємо --}}
    @if (! empty($defaultFields))
        <div id="default_{{ $id }}_fields" class="px-75">
            @foreach ($defaultFields as $field)
                <div class="ui-accordion-header d-flex align-items-center">
                    <img class="pe-1" src="{{ asset($field['icon']) }}" />
                    <p class="mb-0">{{ __($field['label']) }}</p>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Sortable fields – тільки для редагування --}}
    <ul class="sortableList sortableListStyle" data-type-block="{{ $id }}" id="{{ $id }}_fields">
        @if ($mode === 'edit' && count($fields))
            @php
                usort($fields, fn ($a, $b) => $a['id'] <=> $b['id']);
            @endphp

            @foreach ($fields as $field)
                @include(
                    'document-types.update-fields',
                    [
                        'typeBlock' => $field['typeBlock'] ?? $id,
                    ]
                )
            @endforeach
        @endif
    </ul>
</div>
