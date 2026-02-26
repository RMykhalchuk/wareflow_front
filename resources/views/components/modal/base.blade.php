@props([
    'id' => 'custom-modal',
    'size' => 'modal-md',
    'style' => '',
    'class' => '',
])

<div
    class="modal fade {{ $class }}"
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $id }}Title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered {{ $size }}" style="{{ $style }}">
        <div class="modal-content">
            @isset($header)
                <div class="modal-header justify-content-center pt-4 px-3">
                    {{ $header }}
                </div>
            @endisset

            @isset($body)
                <div class="modal-body px-2-5">
                    {{ $body }}
                </div>
            @endisset

            @isset($footer)
                <div class="modal-footer border-0 pb-4 px-3">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
