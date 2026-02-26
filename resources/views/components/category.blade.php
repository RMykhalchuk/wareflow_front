@php
    $isBlocked = $category->has_goods || $category->hasGoodsInChildren();
@endphp

<div class="accordion-item category-data {{ $category->isChild() ? 'border-0' : '' }}">
    <h2
        class="accordion-header accordion-header-custom-goods d-flex align-items-center"
        id="heading{{ $category->id }}"
    >
        <button
            class="accordion-button {{ $category->children->isEmpty() ? 'accordion-button-custom-empty ps-3' : 'accordion-button-custom' }} collapsed {{ $category->isChild() ? 'pe-2' : 'px-2' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse{{ $category->id }}"
            data-id="{{ $category->id }}"
            data-name="{{ $category->name }}"
            data-has-goods="{{ $isBlocked ? 'true' : 'false' }}"
            data-parent="{{ $category->parent_id }}"
            data-goods-category-id="{{ $category?->goodsCategory?->id ?? '' }}"
            aria-expanded="true"
            aria-controls="collapse{{ $category->id }}"
            {{ $category->isChild() ? 'style=padding-left:' . $i . 'rem;' : '' }}
        >
            <div class="d-flex flex-grow-1 justify-content-start align-items-center">
                <div class="fw-bolder">
                    {{ $category->name }}
                </div>
                @if (! $category->isChild())
                    <div
                        id="good_category_name"
                        class="badge bg-light-secondary ms-50 py-50 px-1 text-truncate text-nowrap"
                        style="max-width: 300px"
                    >
                        <div class="js-system-category-name">
                            {{ $category?->goodsCategory?->name }}
                        </div>
                    </div>
                @endif
            </div>
        </button>

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
    </h2>
    <div
        id="collapse{{ $category->id }}"
        class="accordion-collapse collapse"
        aria-labelledby="heading{{ $category->id }}"
        data-bs-parent="#accordion{{ $category->id }}"
    >
        <div class="accordion" id="accordion{{ $category->id }}">
            @if (! $category->children->isEmpty())
                <div class="accordion-body p-0">
                    @php($i += 1)
                    @if (! $category->children->isEmpty())
                        <x-categories :categories="$category->children" :i="$i"></x-categories>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
