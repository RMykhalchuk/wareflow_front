<div
    style="display: none"
    id="{{ $id ?? 'trash' }}"
    class="{{ $wrapperClass ?? 'col-12 col-lg-4 ps-2 mt-1 mt-lg-0' }}"
>
    <div class="trash flex-column justify-content-center align-items-center">
        <img
            src="{{ asset($iconPath ?? 'assets/icons/entity/document-type/recycle.svg') }}"
            alt="{{ $alt ?? 'Trash icon' }}"
        />
        <h3 class="text-center">
            {!! __($title ?? 'localization.document_types_edit_document_fields_trash_title') !!}
        </h3>
        <p class="text-center">
            {!! __($description ?? 'localization.document_types_edit_document_fields_trash_description') !!}
        </p>
    </div>
</div>
