$('#accordion')
    .accordion({
        header: '.accordion-header',
        collapsible: true,
        active: false,
        animate: 200,
    })
    .find('.dropdown-menu')
    .on('mousedown', function (e) {
        // щоб кліки по меню не закривали акордеон
        e.stopPropagation();
    })
    .end()
    .sortable({
        items: '',
        stop: function (event, ui) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children('p').triggerHandler('focusout');

            // Refresh accordion to handle new order
            $(this).accordion('refresh');
        },
    });

function handleAccordionClick(
    $headers,
    $selectorClick,
    $bodies,
    selectorShowBody,
    $chevrons,
    findBody
) {
    $headers.on('click', $selectorClick, function () {
        var $accordionBody = $(this).closest($bodies).find(findBody);

        // Закриваємо всі інші
        selectorShowBody.not($accordionBody).each(function () {
            if ($(this).is(':visible')) {
                $(this)
                    .stop(true, true)
                    .fadeOut(200, function () {
                        $(this).removeClass('d-block').addClass('d-none');
                    });
            }
        });

        // Тогл для поточного
        if ($accordionBody.is(':visible')) {
            $accordionBody.stop(true, true).fadeOut(200, function () {
                $(this).removeClass('d-block').addClass('d-none');
            });
        } else {
            $accordionBody
                .removeClass('d-none')
                .hide()
                .fadeIn(200, function () {
                    $(this).addClass('d-block');
                });
        }

        // Іконки
        var $accordionChevron = $(this).find('#accordion-chevron');
        $chevrons.not($accordionChevron).removeClass('accordion-chevron-active');
        $accordionChevron.toggleClass('accordion-chevron-active');
    });
}

// Виклик функції для першого набору
handleAccordionClick(
    $('.fields-list'),
    '.group .accordion-header',
    '.group',
    $('.document-field-accordion-body'),
    $('.accordion-chevron-active'),
    '.document-field-accordion-body'
);

// Виклик функції для другого набору
handleAccordionClick(
    $('.document-new-fields'),
    '.accordion-group-field .accordion-header-castom',
    '.accordion-group-field',
    $('.accordion-group-field-body'),
    $('.accordion-group-field-body'),
    '.accordion-group-field-body'
);
