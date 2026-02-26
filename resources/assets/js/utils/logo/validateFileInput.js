import { getLocalizedText } from '../../localization/user/getLocalizedText.js';

export function validateFileInput(inputSelector, assignCallback) {
    $(inputSelector).on('change', function () {
        const file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                // 2 МБ
                alert(getLocalizedText('fileSizeLimitMax'));
                $(this).val(''); // очищаємо вибір файлу
                assignCallback(null);
            } else {
                assignCallback(file);
            }
        } else {
            assignCallback(null);
        }
    });
}
