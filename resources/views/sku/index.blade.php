@extends('layouts.admin')
@section('title', __('localization.sku_index_title'))

@section('before-style')
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.base.css') }}"
        type="text/css"
    />
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css') }}"
        type="text/css"
    />
@endsection

@section('table-js')
    @include('layouts.table-scripts')
    <script type="module" src="{{ asset('assets/js/grid/sku/sku-table.js') }}"></script>
@endsection

@section('content')
    @if ($goodsCount)
        <x-layout.index-table-card
            :title="__('localization.sku_index_goods')"
            {{-- :buttonText="__('localization.sku_index_add_goods')" --}}
            {{-- :buttonRoute="route('sku.create')" --}}
            tableId="sku-table"
        >
            <slot:headerRight>
                <div class="btn-group">
                    <button
                        id="createButton"
                        type="button"
                        class="btn btn-primary d-flex align-items-center"
                        style="cursor: pointer"
                    >
                        <img
                            class="plus-icon me-50"
                            src="{{ asset('assets/icons/utils/plus.svg') }}"
                            alt="plus"
                        />
                        <span id="createButtonText">
                            {{ __('localization.sku_index_add_goods') }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary dropdown-toggle dropdown-toggle-split no-caret py-1 px-2"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        style="
                            --bs-dropdown-toggle-after-bg: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;);
                            background-image: var(--bs-dropdown-toggle-after-bg);
                            background-repeat: no-repeat;
                            background-position: center;
                            background-size: 16px;
                        "
                    >
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item js-create-dropdown-item"
                                href="#"
                                data-route="{{ route('sku.create') }}"
                            >
                                {{ __('localization.sku_index_add_goods') }}
                            </a>
                        </li>
                        <li>
                            <a
                                class="dropdown-item js-create-dropdown-item"
                                href="#"
                                data-route="{{ route('sku.kits') }}"
                            >
                                {{ __('localization.sku_index_create_kits') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </slot:headerRight>
        </x-layout.index-table-card>
    @else
        <x-layout.index-empty-message
            :title="__('localization.sku_index_no_goods')"
            :message="__('localization.sku_index_no_goods_message')"
            :buttonText="__('localization.sku_index_create_goods')"
            :buttonRoute="route('sku.create')"
        />
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#sku-table'), '', 50, 65);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#sku-table'));
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createButton = document.getElementById('createButton');
            const createButtonText = document.getElementById('createButtonText');
            let currentRoute = '{{ route('sku.create') }}'; // значення за замовчуванням
            const dropdownItems = document.querySelectorAll('.js-create-dropdown-item');

            dropdownItems.forEach((item) => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Оновлюємо текст кнопки
                    createButtonText.textContent = this.textContent.trim();
                    currentRoute = this.dataset.route;
                });
            });

            createButton.addEventListener('click', function () {
                window.location.href = currentRoute;
            });
        });
    </script>
@endsection
