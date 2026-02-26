@php
    // Якщо змінна не передана — поставити false.
    // Також пробуємо прочитати можливі назви атрибуту (kebab / camel)
    $raw = $useButtonInsteadOfForm ?? ($attributes->get('use-button-instead-of-form') ?? ($attributes->get('useButtonInsteadOfForm') ?? false));
    $useButtonInsteadOfForm = filter_var($raw, FILTER_VALIDATE_BOOLEAN);
@endphp

<div
    class="modal fade"
    id="{{ $modalId }}"
    tabindex="-1"
    aria-labelledby="{{ $modalId }}Title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 500px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fw-bolder" id="{{ $modalId }}Title">
                    {{ __($title) }}
                </h4>
            </div>

            <div class="modal-body">
                <p>{{ __($description) }}</p>
            </div>

            <div class="modal-footer">
                @if ($useButtonInsteadOfForm)
                    <div class="d-flex justify-content-end w-100">
                        <a
                            class="btn btn-flat-secondary float-start mr-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __($cancelText) }}
                        </a>
                        <button id="{{ $modalId }}-btn" type="button" class="btn btn-primary">
                            {{ __($confirmText) }}
                        </button>
                    </div>
                @else
                    <form
                        class="d-flex justify-content-end w-100"
                        method="POST"
                        action="{{ $action }}"
                    >
                        @csrf
                        @method('DELETE')

                        <a
                            class="btn btn-flat-secondary float-start mr-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __($cancelText) }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ __($confirmText) }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
