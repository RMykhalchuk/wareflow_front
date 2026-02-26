<div
    class="accordion-header category-data accordion-header-custom-goods d-flex align-items-center"
    style="height: 50px"
    id="headingBorder{{ $category->id }}SubChild"
>
    <div
        style="padding-left: {{ $i + 2 }}rem"
        class="accordion-button {{ $category->children->isEmpty() ? 'accordion-button-custom-empty ' : 'accordion-button-custom' }} accordion-button-custom-end collapsed pe-2"
        data-id="{{ $category->id }}"
        data-name="{{ $category->name }}"
        data-parent="{{ $category->parent_id }}"
        data-has-goods="{{ $category->has_goods }}"
        type="button"
    >
        <span class="d-flex flex-grow-1 justify-content-between">
            <span class="fw-bolder">{{ $category->name }}</span>
        </span>
    </div>
    <div
        class="gap-1 d-flex accordion-button-custom-action align-items-center pe-2"
        style="height: 50px"
    >
        <span
            data-bs-toggle="modal"
            id="add_category_goods_button"
            data-bs-target="#add_category_goods"
            type="submit"
            class="px-25"
        >
            <img src="{{ asset('assets/icons/utils/plus.svg') }}" alt="plus" />
        </span>
        <span
            data-bs-toggle="modal"
            id="edit_category_goods_button"
            data-bs-target="#edit_category_goods"
            type="submit"
            class="px-25"
        >
            <img src="{{ asset('assets/icons/entity/type-goods/edit.svg') }}" alt="edit" />
        </span>
    </div>
</div>
