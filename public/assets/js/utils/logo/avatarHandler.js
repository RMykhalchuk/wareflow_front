import { getLocalizedText } from '../../localization/user/getLocalizedText.js';

// avatarHandler.js
export function setupAvatarInput(inputSelector = '#logo', options = {}) {
    const {
        maxSizeMB = 0.8,
        onChange = (file) => {},
        emptySrc = '/assets/images/avatar_empty.png', // шлях до пустого аватара
    } = options;

    const $input = $(inputSelector);
    const $preview = $(`${inputSelector}-img`);
    const $reset = $(`${inputSelector}-reset`);

    // Скидання аватара
    $reset.on('click', () => {
        onChange(null);
        $preview.attr('src', emptySrc);
        $input.val(''); // Очищаємо інпут, щоб можна було повторно завантажити той же файл
    });

    // Обробка зміни файлу
    $input.on('change', function () {
        const file = this.files[0];
        if (!file) return;

        const size = (file.size / 1024 / 1024).toFixed(2);
        if (size > maxSizeMB) {
            $input.val(''); // очищаємо інпут
            alert(getLocalizedText('fileSizeLimit'));
            return;
        }

        // Оновлюємо avatar через callback
        onChange(file);

        const reader = new FileReader();
        reader.onload = (e) => {
            $preview.attr('src', e.target.result);
        };
        reader.readAsDataURL(file);

        $input.val(''); // Очищаємо інпут, щоб можна було знову вибрати той же файл
    });
}
