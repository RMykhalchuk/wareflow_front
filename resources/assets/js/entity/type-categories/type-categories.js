import { getLocalizedText } from '../../localization/type-categories/getLocalizedText.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';

const locale = getCurrentLocaleFromUrl();
const baseUrl = window.location.origin;

const url =
    locale === 'en'
        ? baseUrl // без префіксу
        : `${baseUrl + '/' + locale}`;

document.addEventListener('DOMContentLoaded', function () {
    var searchField = document.getElementById('js-search-field');
    var searchShowButton = document.getElementById('js-search-show-field');
    var typeGoodsLineImage = document.querySelector('.js-type-goods-line');

    searchShowButton.addEventListener('click', function () {
        searchField.classList.remove('d-none');
        searchShowButton.classList.add('d-none');
        typeGoodsLineImage.classList.add('d-none');
    });

    document.addEventListener('click', function (event) {
        var isClickInsideSearchField = searchField.contains(event.target);
        var isClickOnShowButton = searchShowButton.contains(event.target);

        if (!isClickInsideSearchField && !isClickOnShowButton) {
            searchField.classList.add('d-none');
            searchShowButton.classList.remove('d-none');
            typeGoodsLineImage.classList.remove('d-none');
        }
    });
});

$(document).ready(function () {
    var $searchInput = $('#searchListGoods');
    var $accordionItems = $('.accordion-item');

    $searchInput.on('input', function () {
        var searchText = $(this).val().toLowerCase();

        $accordionItems.each(function () {
            var $accordionItem = $(this);
            var accordionText = $accordionItem.find('.fw-bolder').text().toLowerCase();

            if (accordionText.includes(searchText)) {
                $accordionItem.show();
            } else {
                $accordionItem.hide();
            }
        });
    });
});

$(function () {
    $('.category-data').each(function () {
        const $item = $(this);
        const $badge = $item.find('[id="good_category_name"]').first();
        if (!$badge.length) return;

        if ($.trim($badge.text()) !== '') return;

        const $root = $item.parents('.category-data').last();
        const $rootBadge = ($root.length ? $root : $item).find('[id="good_category_name"]').first();
        const rootText = $.trim($rootBadge.text());

        if (rootText) {
            $badge.text(rootText);
        }
    });
});

$(function () {
    const $modal = $('#add_category_goods');

    $modal.on('show.bs.modal', function (e) {
        const $trigger = $(e.relatedTarget);
        let parentId = '';

        if ($trigger && $trigger.length) {
            const $category = $trigger.closest('.category-data');

            if ($category.length) {
                const $accordionButton = $category.find('.accordion-button').first();
                parentId = $accordionButton.data('id') || '';
            }
        }

        $('#add_parent_id').val(parentId);

        const $wrapGoods = $('#add_goods_category_wrap');
        const $selGoods = $('#add_goods_category');

        if (parentId) {
            $wrapGoods.addClass('d-none');
            $selGoods.prop('disabled', true).val('').trigger('change');
        } else {
            $wrapGoods.removeClass('d-none');
            $selGoods.prop('disabled', false);
        }
    });

    $modal.on('hidden.bs.modal', function () {
        $('#add_parent_id').val('');

        const $wrapGoods = $('#add_goods_category_wrap');
        const $selGoods = $('#add_goods_category');
        $wrapGoods.removeClass('d-none');
        $selGoods.prop('disabled', false).val('').trigger('change');
    });
});

$(document).on('click', function (event) {
    var $target = $(event.target);

    var isEditButton =
        $target.is('#edit_category_goods_button') ||
        $target.closest('#edit_category_goods_button').length;
    var isAddButton =
        $target.is('#add_category_goods_button') ||
        $target.closest('#add_category_goods_button').length;
    var isCategoryData = $target.is('.category-data') || $target.closest('.category-data').length;

    if (isEditButton || isAddButton || isCategoryData) {
        var $accordionButton = $target.closest('.category-data').find('.accordion-button').first();

        if (isEditButton || (isCategoryData && !isAddButton)) {
            var id = $accordionButton.data('id');
            var name = $accordionButton.data('name');
            var parent = $accordionButton.data('parent');
            var hasGoods = $accordionButton.data('has-goods');

            var goodsCategoryId = $accordionButton.data('goods-category-id') || '';
            var urlBase = url + '/type-categories/' + id;

            $('#edit_category_goods form').attr('action', urlBase);
            $('#edit_category_goods #edit_name_goods').val(name);
            $('#edit_goods_category').val(goodsCategoryId).trigger('change');

            const $selGoods = $('#edit_goods_category');
            if (parent) {
                $selGoods.closest('.col-12').addClass('d-none');
                $selGoods.prop('disabled', true);
            } else {
                $selGoods.closest('.col-12').removeClass('d-none');
                $selGoods.prop('disabled', false);
            }

            // 🔒 Блокуємо кнопку, якщо є товари
            const $deleteButton = $('#edit_category_goods .js-delete-category');
            if (hasGoods) {
                $deleteButton.prop('disabled', true).addClass('disabled');
            } else {
                $deleteButton.prop('disabled', false).removeClass('disabled');
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var actionButtons = document.querySelectorAll('.accordion-button-custom-action');

    actionButtons.forEach(function (actionButton) {
        actionButton.addEventListener('mouseenter', function () {
            var accordionButton = this.closest('.accordion-header-custom-goods').querySelector(
                '.accordion-button'
            );
            if (accordionButton) {
                accordionButton.classList.add('custom-hover-type-goods');
            }
        });

        actionButton.addEventListener('mouseleave', function () {
            var accordionButton = this.closest('.accordion-header-custom-goods').querySelector(
                '.accordion-button'
            );
            if (accordionButton) {
                accordionButton.classList.remove('custom-hover-type-goods');
            }
        });
    });
});

$(function () {
    const $modal = $('#add_category_goods');

    $modal.on('show.bs.modal', function (e) {
        const $trigger = $(e.relatedTarget);
        let parentId = '';
        if ($trigger && $trigger.length) {
            const $category = $trigger.closest('.category-data');
            if ($category.length) {
                const $btn = $category.find('.accordion-button').first();
                parentId = $btn.data('id') || '';
            }
        }
        $('#add_category_goods_title').text(
            parentId ? getLocalizedText('ADD_TITLE_CHILD') : getLocalizedText('ADD_TITLE_ROOT')
        );
    });

    $modal.on('hidden.bs.modal', function () {
        $('#add_category_goods_title').text(getLocalizedText('ADD_TITLE_ROOT'));
    });
});

$(document).on('click', function (event) {
    const $t = $(event.target);

    const isEditButton =
        $t.is('#edit_category_goods_button') || $t.closest('#edit_category_goods_button').length;
    const isAddButton =
        $t.is('#add_category_goods_button') || $t.closest('#add_category_goods_button').length;
    const isCategoryData = $t.is('.category-data') || $t.closest('.category-data').length;

    if (isEditButton || (isCategoryData && !isAddButton)) {
        const $btn = $t.closest('.category-data').find('.accordion-button').first();
        const parent = $btn.data('parent');
        $('#edit_category_goods_title').text(
            parent ? getLocalizedText('EDIT_TITLE_CHILD') : getLocalizedText('EDIT_TITLE_ROOT')
        );
    }
});

$(document).on('click', '#edit_category_goods_button', function () {
    var $btn = $(this).closest('.category-data').find('.accordion-button').first();
    var id = $btn.data('id');
    var name = $btn.data('name');
    var parent = $btn.data('parent');
    var goodsCategoryId = $btn.data('goods-category-id') || '';

    // Заповнюємо hidden input і інші поля
    $('#edit_category_id').val(id);
    $('#edit_name_goods').val(name);
    $('#edit_goods_category').val(goodsCategoryId).trigger('change');

    const $selGoods = $('#edit_goods_category');
    if (parent) {
        $selGoods.closest('.col-12').addClass('d-none');
        $selGoods.prop('disabled', true);
    } else {
        $selGoods.closest('.col-12').removeClass('d-none');
        $selGoods.prop('disabled', false);
    }
});

$(document).on('click', '.js-delete-category', async function (event) {
    event.preventDefault();

    var id = $('#edit_category_id').val();
    if (!id) {
        console.error(getLocalizedText('ERROR_NO_ID'));
        return;
    }

    const urlBase = `${url}/type-categories/${id}/delete`;

    try {
        const response = await fetch(urlBase, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',
            },
        });

        if (response.ok) {
            location.reload();
        } else {
            const errorText = await response.text();
            console.error(getLocalizedText('ERROR_DELETE'), errorText);
            alert(getLocalizedText('ALERT_DELETE_FAIL'));
        }
    } catch (error) {
        console.error(getLocalizedText('ERROR_DELETE'), error);
        alert(getLocalizedText('ALERT_DELETE_ERROR'));
    }
});

$(function () {
    const MAX_DEPTH = 5;

    function calcDepth($node) {
        return $node.parents('.category-data').length + 1;
    }

    function toggleAddBtnForNode($node) {
        const depth = calcDepth($node);
        const $addBtn = $node.find('#add_category_goods_button').first();
        if (!$addBtn.length) return;

        if (depth >= MAX_DEPTH) {
            $addBtn.addClass('d-none').prop('disabled', true).attr('aria-disabled', 'true');
        } else {
            $addBtn.removeClass('d-none').prop('disabled', false).removeAttr('aria-disabled');
        }
    }

    function applyDepthLimit(ctx = document) {
        $(ctx)
            .find('.category-data')
            .each(function () {
                toggleAddBtnForNode($(this));
            });
    }

    applyDepthLimit();

    const $tree = $('#goods_categories_list, .categories-root').first();

    if ($tree.length) {
        const mo = new MutationObserver(() => applyDepthLimit($tree[0]));
        mo.observe($tree[0], { childList: true, subtree: true });
    }

    $('#add_category_goods').on('show.bs.modal', function (e) {
        const $trigger = $(e.relatedTarget);
        if (!$trigger || !$trigger.length) return;

        const $node = $trigger.closest('.category-data');
        if (!$node.length) return;

        const depth = calcDepth($node);
        if (depth >= MAX_DEPTH) {
            e.preventDefault();
            e.stopImmediatePropagation();
            alert(
                getLocalizedText?.('ALERT_MAX_DEPTH') ||
                    'Досягнуто максимальний рівень вкладеності.'
            );
        }
    });
});
