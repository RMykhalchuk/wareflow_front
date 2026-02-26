// Анімація пошуку
document.addEventListener('DOMContentLoaded', function () {
    var searchField = document.getElementById('js-search-field');
    var searchShowButton = document.getElementById('js-search-show-field');
    var typeGoodsLineImage = document.querySelector('.js-type-goods-line'); // Додаємо змінну для зображення

    // Обробник кліку на кнопку для показу поля пошуку
    searchShowButton.addEventListener('click', function () {
        searchField.classList.remove('d-none');
        searchShowButton.classList.add('d-none');
        typeGoodsLineImage.classList.add('d-none'); // Ховаємо зображення
    });

    // Обробник кліку поза областю поля пошуку
    document.addEventListener('click', function (event) {
        var isClickInsideSearchField = searchField.contains(event.target);
        var isClickOnShowButton = searchShowButton.contains(event.target);

        if (!isClickInsideSearchField && !isClickOnShowButton) {
            searchField.classList.add('d-none');
            searchShowButton.classList.remove('d-none');
            typeGoodsLineImage.classList.remove('d-none'); // Показуємо зображення
        }
    });
});

//Пошук
$(document).ready(function () {
    // Отримуємо посилання на елементи DOM
    var $searchInput = $('#searchListGoods');
    var $accordionItems = $('.accordion-item');

    // Додаємо обробник події введення тексту в полі пошуку
    $searchInput.on('input', function () {
        var searchText = $(this).val().toLowerCase();

        // Перевіряємо кожен елемент і приховуємо ті, які не відповідають тексту пошуку
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

$(document).on('click', function (event) {
    var $target = $(event.target);

    // Check if the clicked element is one of the specific buttons or within a .category-data element
    var isEditButton =
        $target.is('#edit_category_goods_button') ||
        $target.closest('#edit_category_goods_button').length;
    var isAddButton =
        $target.is('#add_category_goods_button') ||
        $target.closest('#add_category_goods_button').length;
    var isCategoryData = $target.is('.category-data') || $target.closest('.category-data').length;

    // If the click is relevant to our handler
    if (isEditButton || isAddButton || isCategoryData) {
        var $accordionButton = $target.closest('.category-data').find('.accordion-button').first();

        // Handle the edit button scenario
        if (isEditButton || (isCategoryData && !isAddButton)) {
            // Fetch data from the .accordion-button for editing
            var id = $accordionButton.data('id');
            var name = $accordionButton.data('name');
            var parent = $accordionButton.data('parent');

            //console.log("Edit:", id, name, parent);

            // Update the edit form
            var baseUrl = window.location.origin;
            var url = baseUrl + '/type-goods/' + id;

            $('#edit_category_goods form').attr('action', url);
            $('#edit_category_goods #edit_name_goods').val(name);
            $('#edit_goods_category').val(parent).trigger('change');
        }

        // Handle the add button scenario
        if (isAddButton) {
            var parent = $accordionButton.data('parent');
            //console.log("Add: Parent ID", parent);
            $('#add_goods_category').val(parent).trigger('change');
        }
    }
});

// Хавер для категорій
document.addEventListener('DOMContentLoaded', function () {
    // Select all the `.accordion-button-custom-action` elements
    var actionButtons = document.querySelectorAll('.accordion-button-custom-action');

    actionButtons.forEach(function (actionButton) {
        // Add mouseenter event listener
        actionButton.addEventListener('mouseenter', function () {
            var accordionButton = this.closest('.accordion-header-custom-goods').querySelector(
                '.accordion-button'
            );
            if (accordionButton) {
                accordionButton.classList.add('custom-hover-type-goods');
            }
        });

        // Add mouseleave event listener
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
