@extends('layouts.admin')
@section('title', __('localization.type_categories.index.title'))

@section('page-style')
    
@endsection

@section('before-style')
    
@endsection

@section('content')
    @php($i = 6)
    <x-layout.container fluid>
        <x-slot:slot>
            <div class="row mx-0" style="column-gap: 144px">
                <div
                    class="col-12 col-sm-12 col-md-3 col-lg-3 col-xxl-3 px-0"
                    style="min-width: 208px; max-width: fit-content"
                >
                    @include('layouts.setting')
                </div>

                <div
                    class="col-12 col-sm-12 col-md-12 col-lg-9 col-xxl-9 px-0"
                    style="max-width: 798px"
                >
                    <div class="tab-content card pb-0">
                        <div
                            role="tabpanel"
                            class="tab-pane mb-0 active"
                            id="vertical-pill-5"
                            aria-labelledby="stacked-pill-5"
                            aria-expanded="true"
                        >
                            <div id="all-regulation">
                                <div
                                    class="p-2 d-flex flex-wrap gap-1 justify-content-between align-items-center"
                                >
                                    <h4 class="fw-bolder mb-0">
                                        {{ __('localization.setting_nav_item_2') }}
                                    </h4>
                                    <div
                                        style="height: 38px"
                                        class="d-flex col-12 col-sm-12 col-md-auto flex-grow-1 justify-content-end gap-1 align-items-center"
                                    >
                                        <div>
                                            <div
                                                id="js-search-field"
                                                class="input-group d-none input-group-merge"
                                            >
                                                <span
                                                    class="input-group-text"
                                                    id="basic-addon-search2"
                                                >
                                                    <i data-feather="search"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control ps-50"
                                                    id="searchListGoods"
                                                    placeholder="{{ __('localization.type_categories.index.search_placeholder') }}"
                                                    aria-label="{{ __('localization.type_categories.index.search_aria_label') }}"
                                                    aria-describedby="basic-addon-search2"
                                                />
                                            </div>
                                            <button class="btn p-50" id="js-search-show-field">
                                                <i data-feather="search"></i>
                                            </button>
                                        </div>

                                        <div class="js-type-goods-line">
                                            <img
                                                height=""
                                                src="{{ asset('assets/icons/entity/type-goods/line.svg') }}"
                                                alt="line"
                                            />
                                        </div>

                                        <div>
                                            <button
                                                data-bs-toggle="modal"
                                                id="add_category_goods_button"
                                                data-bs-target="#add_category_goods"
                                                type="submit"
                                                class="btn btn-outline-secondary d-flex align-items-center justify-content-center"
                                            >
                                                <i data-feather="plus" class="mr-1"></i>
                                                {{ __('localization.type_categories.index.create_category') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="list-type-goods">
                                @foreach ($categories as $category)
                                    <div
                                        class="accordion accordion-border"
                                        data-id="{{ $category->id }}"
                                        id="accordion{{ $category->id }}"
                                    >
                                        <x-category :category="$category" :i="$i"></x-category>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    {{-- Modal Add Category --}}
    <x-modal.base id="add_category_goods" size="modal-lg" style="max-width: 555px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                <span id="add_category_goods_title">
                    {{ __('localization.type_categories.add_category.create') }}
                </span>
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <form
                class="js-modal-form"
                action="{{ route('type-categories.store') }}"
                method="POST"
            >
                @csrf
                <input type="hidden" id="add_parent_id" name="parent_id" value="" />

                <x-form.input-text
                    id="add_name_goods"
                    name="name"
                    label="{{ __('localization.type_categories.add_category.name') }}"
                    placeholder="{{ __('localization.type_categories.add_category.placeholder') }}"
                    class="col-12 mb-1"
                />

                <div id="add_goods_category_wrap">
                    <x-form.select
                        id="add_goods_category"
                        name="goods_category_id"
                        label="{{ __('localization.type_categories.add_category.belongs_to') }}"
                        placeholder="{{ __('localization.type_categories.add_category.select_placeholder') }}"
                        data-dictionary="goods_category"
                        class="col-12 mb-1"
                    />
                </div>

                <div class="col-12">
                    <div class="d-flex float-end mb-2">
                        <button
                            type="button"
                            class="btn link-primary text-secondary me-1"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __('localization.type_categories.add_category.cancel') }}
                        </button>

                        <button type="submit" class="btn btn-primary" id="save_new_goods_category">
                            {{ __('localization.type_categories.add_category.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal.base>

    {{-- Modal Edit Category --}}
    <x-modal.base id="edit_category_goods" size="modal-lg" style="max-width: 555px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                <span id="edit_category_goods_title">
                    {{ __('localization.type_categories.edit_category.edit') }}
                </span>
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <form class="js-modal-form2" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_category_id" name="category_id" value="" />

                <x-form.input-text
                    id="edit_name_goods"
                    name="name"
                    label="{{ __('localization.type_categories.edit_category.name') }}"
                    placeholder="{{ __('localization.type_categories.edit_category.placeholder') }}"
                    class="col-12 mb-1"
                />

                <x-form.select
                    id="edit_goods_category"
                    name="goods_category_id"
                    label="{{ __('localization.type_categories.edit_category.belongs_to') }}"
                    placeholder="{{ __('localization.type_categories.edit_category.select_placeholder') }}"
                    data-dictionary="goods_category"
                    class="col-12 mb-1"
                />

                <div class="col-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <button
                                type="button"
                                class="btn btn-flat-danger gap-50 d-flex align-items-center js-delete-category"
                            >
                                <i data-feather="trash-2"></i>
                                {{ __('localization.type_categories.edit_category.delete') }}
                            </button>
                        </div>

                        <div class="">
                            <button
                                type="button"
                                class="btn link-primary text-secondary me-1"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                {{ __('localization.type_categories.edit_category.cancel') }}
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="edit_new_goods_category"
                            >
                                {{ __('localization.type_categories.edit_category.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script
        type="module"
        src="{{ asset('assets/js/entity/type-categories/type-categories.js') }}"
    ></script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script type="module">
        import { translateSystemTypeGoodsName } from '{{ asset('assets/js/localization/type-categories/translateSystemTypeGoodsName.js') }}';

        translateSystemTypeGoodsName('.js-system-category-name');
    </script>
@endsection
