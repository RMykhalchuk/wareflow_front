export function togglePlaceholder(listSelector, placeholderSelector) {
    const $list = $(listSelector);
    const $placeholder = $(placeholderSelector);

    const hasItems = $list.children('li').not($placeholder).length > 0;

    if (hasItems) {
        $placeholder.addClass('d-none');
    } else {
        $placeholder.removeClass('d-none');
    }
}
