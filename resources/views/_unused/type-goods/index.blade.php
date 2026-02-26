@extends('layouts.admin')
@section('title', __('localization.type_goods_index_title'))

@section('page-style')
    
@endsection

@section('before-style')
    
@endsection

@section('content')
    @php($i = 3)
    <div class="container-fluid px-3">
        <div class="row" style="column-gap: 144px">
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
                                    {{ __('localization.type_goods_index_categories') }}
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
                                            <span class="input-group-text" id="basic-addon-search2">
                                                <i data-feather="search"></i>
                                            </span>
                                            <input
                                                type="text"
                                                class="form-control ps-50"
                                                id="searchListGoods"
                                                placeholder="{{ __('localization.type_goods_index_search_placeholder') }}"
                                                aria-label="{{ __('localization.type_goods_index_search_aria_label') }}"
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
                                            class="btn btn-outline-primary d-flex align-items-center justify-content-center"
                                        >
                                            <i data-feather="plus" class="mr-1"></i>
                                            {{ __('localization.type_goods_index_create_category') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="list-type-goods">
                            @foreach ($categories as $category)
                                <div
                                    class="accordion accordion-border"
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

        <x-modal.base id="add_category_goods" size="modal-lg" style="max-width: 555px !important">
            <x-slot name="header">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.type_goods_index_add_category_create') }}
                </x-ui.section-card-title>
            </x-slot>

            <x-slot name="body">
                <form class="js-modal-form" action="{{ route('type-goods.store') }}" method="POST">
                    @csrf

                    <x-form.input-text
                        id="add_name_goods"
                        name="name"
                        label="localization.type_goods_index_add_category_name"
                        placeholder="localization.type_goods_index_add_category_placeholder"
                        class="col-12 mb-1"
                    />

                    <x-form.select
                        id="add_goods_category"
                        name="parent_id"
                        label="localization.type_goods_index_add_category_belongs_to"
                        placeholder="localization.type_goods_index_add_category_select_placeholder"
                        data-dictionary="goods_category"
                        class="col-12 mb-1"
                    />

                    <div class="col-12">
                        <div class="d-flex float-end mb-2">
                            <button
                                type="button"
                                class="btn link-primary text-secondary me-1"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                {{ __('localization.type_goods_index_add_category_cancel') }}
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="save_new_goods_category"
                            >
                                {{ __('localization.type_goods_index_add_category_save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal.base>

        <x-modal.base id="edit_category_goods" size="modal-lg" style="max-width: 555px !important">
            <x-slot name="header">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.type_goods_index_edit_category_edit') }}
                </x-ui.section-card-title>
            </x-slot>

            <x-slot name="body">
                <form class="js-modal-form2" method="POST">
                    @csrf
                    @method('PUT')

                    <x-form.input-text
                        id="edit_name_goods"
                        name="name"
                        label="localization.type_goods_index_edit_category_name"
                        placeholder="localization.type_goods_index_edit_category_placeholder"
                        class="col-12 mb-1"
                    />

                    <x-form.select
                        id="edit_goods_category"
                        name="parent_id"
                        label="localization.type_goods_index_edit_category_belongs_to"
                        placeholder="localization.type_goods_index_edit_category_select_placeholder"
                        data-dictionary="goods_category"
                        class="col-12 mb-1"
                    />

                    <div class="col-12">
                        <div class="d-flex float-end mb-2">
                            <button
                                type="button"
                                class="btn link-primary text-secondary me-1"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                {{ __('localization.type_goods_index_edit_category_cancel') }}
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="edit_new_goods_category"
                            >
                                {{ __('localization.type_goods_index_edit_category_save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal.base>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/entity/type-goods/type-goods.js') }}"></script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script type="module">
        import { translateSystemTypeGoodsName } from '{{ asset('assets/js/localization/type-goods/translateSystemTypeGoodsName.js') }}';

        translateSystemTypeGoodsName('.js-system-category-name');
    </script>
@endsection
