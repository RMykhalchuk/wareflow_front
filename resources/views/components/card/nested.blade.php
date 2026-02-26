@props([
    'headerAttributes' => [],
    'bodyAttributes' => [],
])

@php
    $headerAttr = new \Illuminate\View\ComponentAttributeBag($headerAttributes);
    $bodyAttr = new \Illuminate\View\ComponentAttributeBag($bodyAttributes);
@endphp

<div {{ $attributes->merge(['class' => 'card my-2']) }}>
    <div class="row mx-0">
        <div class="card col-12 p-2 mb-0">
            @isset($header)
                <div {{ $headerAttr->merge(['class' => 'card-header p-0']) }}>
                    {{ $header }}
                </div>
            @endisset

            @isset($body)
                <div {{ $bodyAttr->merge(['class' => 'card-body p-0 mt-1']) }}>
                    <div class="row mx-0">
                        {{ $body }}
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>
