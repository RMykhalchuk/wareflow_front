<x-modal.base id="add-type" size="modal-lg" style="max-width: 680px !important">
    <x-slot name="header">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.document_types.modal.select_type_title') }}
            </x-ui.section-card-title>
            <p class="text-muted">
                {{ __('localization.document_types.modal.select_type_description') }}
            </p>
        </div>
    </x-slot>

    <x-slot name="body">
        <div class="row g-2 mx-0 mb-4">
            <div class="col-6">
                <a
                    href="{{ route('document-type.create', ['document_kind' => 'arrival']) }}"
                    class="card h-100 border shadow-sm text-decoration-none"
                >
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-2">
                            {{ __('localization.document_types.modal.incoming_title') }}
                        </h5>
                        <p class="mb-0 text-muted">
                            {{ __('localization.document_types.modal.incoming_description') }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a
                    href="{{ route('document-type.create', ['document_kind' => 'outcome']) }}"
                    class="card h-100 border shadow-sm text-decoration-none"
                >
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-2">
                            {{ __('localization.document_types.modal.outgoing_title') }}
                        </h5>
                        <p class="mb-0 text-muted">
                            {{ __('localization.document_types.modal.outgoing_description') }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a
                    href="{{ route('document-type.create', ['document_kind' => 'inner']) }}"
                    class="card h-100 border shadow-sm text-decoration-none"
                >
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-2">
                            {{ __('localization.document_types.modal.internal_title') }}
                        </h5>
                        <p class="mb-0 text-muted">
                            {{ __('localization.document_types.modal.internal_description') }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a
                    href="{{ route('document-type.create', ['document_kind' => 'neutral']) }}"
                    class="card h-100 border shadow-sm text-decoration-none"
                >
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-2">
                            {{ __('localization.document_types.modal.neutral_title') }}
                        </h5>
                        <p class="mb-0 text-muted">
                            {{ __('localization.document_types.modal.neutral_description') }}
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </x-slot>
</x-modal.base>
