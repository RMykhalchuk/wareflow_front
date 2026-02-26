import { getLocalizedText } from '../../../localization/document-type/getLocalizedText.js';

$(document).ready(function () {
    const $modalTitle = $('#custom-modal-title');
    const $fieldTypeList = $('.field-type-list');
    const $createBtn = $('#create-custom-btn');
    const $backBtn = $('#back-custom-btn');
    const $additionalSettings = $('.additional-settings');

    // 🔹 Допоміжні функції
    function resetModal() {
        $additionalSettings.add($createBtn).add($backBtn).hide();
        $fieldTypeList.show();
        $modalTitle.text(getLocalizedText('configuratorCustomModalCloseButtonCustomModalTitle'));
        $('.field-type.field-type-active').removeClass('field-type-active');
    }

    function showAdditionalSettings($item) {
        const activeLiId = $item.attr('id');
        const activeLiTitle = $item.find('h5').text();

        $fieldTypeList.hide();
        $('#additional-settings-' + activeLiId)
            .add($createBtn)
            .add($backBtn)
            .show();

        $modalTitle.text(
            getLocalizedText('configuratorShowAdditionalSettingsCustomModalTitle') +
                ' "' +
                activeLiTitle +
                '"'
        );
    }

    function showFieldsList() {
        const $active = $('li.field-type-active');
        if ($active.length) {
            const activeLiId = $active.attr('id');
            $('#additional-settings-' + activeLiId)
                .add($createBtn)
                .add($backBtn)
                .hide();

            $active.removeClass('field-type-active'); // 🔹 ось цього бракувало
        }

        $fieldTypeList.show();
        $modalTitle.text(getLocalizedText('configuratorShowFieldsTypesListButtonCustomModalTitle'));
    }

    // 🔹 Ініціалізація
    resetModal();

    // 🔹 Обробники подій
    $('#custom-close-btn').on('click', resetModal);

    $('.field-type').on('click', function () {
        const $this = $(this);

        if (!$this.hasClass('field-type-active')) {
            $('.field-type.field-type-active').removeClass('field-type-active');
            $this.addClass('field-type-active');
            showAdditionalSettings($this);
        }
    });

    $backBtn.on('click', showFieldsList);
});
