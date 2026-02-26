<div
    class="modal fade text-start"
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="modalLabel_{{ $id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
        <div class="modal-content">
            <div class="card popup-card p-2">
                <h4 class="fw-bolder">{{ $title }}</h4>
                <div class="card-body row mx-0 p-0">
                    <p class="my-2 p-0">{!! $content !!}</p>
                    <div class="col-12">
                        <div class="d-flex float-end">
                            <button
                                type="button"
                                class="btn btn-link cancel-btn"
                                data-bs-dismiss="modal"
                            >
                                {{ $cancelText }}
                            </button>
                            <a
                                class="btn btn-primary"
                                href="{{ app()->getLocale() === 'en' ? url(ltrim($route, '/')) : url(app()->getLocale() . '/' . ltrim($route, '/')) }}"
                            >
                                {{ $confirmText }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
