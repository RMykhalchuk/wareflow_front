import { getLocalizedText } from '../localization/sku/getLocalizedText.js';

function getUkrainianUnit(unit, count) {
    const units = {
        day: getLocalizedText('units_day'),
        hour: getLocalizedText('units_hour'),
        piece: getLocalizedText('units_piece'),
        // додай інші одиниці при потребі
    };

    const forms = units[unit];
    if (!forms) return unit;

    const mod10 = count % 10;
    const mod100 = count % 100;

    if (mod10 === 1 && mod100 !== 11) return forms[0];
    if (mod10 >= 2 && mod10 <= 4 && (mod100 < 10 || mod100 >= 20)) return forms[1];
    return forms[2];
}

document.querySelectorAll('.tag-input').forEach((input) => {
    const container = input.closest('.tag-input-wrapper');
    const hiddenInput = container.querySelector('input[type="hidden"]');
    const unit = container.dataset.unit || null;

    let tags = [];

    try {
        // Вхідні значення — це числа (рядки чисел)
        tags = JSON.parse(hiddenInput?.value || '[]').filter((v) => /^\d+$/.test(v));
    } catch (e) {
        tags = [];
    }

    const updateHiddenInput = () => {
        if (!hiddenInput) return;
        // Зберігаємо в прихований інпут лише масив чисел у вигляді рядків
        hiddenInput.value = JSON.stringify(tags);
    };

    const renderTag = (text) => {
        const tag = document.createElement('div');
        tag.className = 'tag';

        const number = parseInt(text);
        let displayText = text;
        if (unit && !isNaN(number)) {
            const displayUnit = getUkrainianUnit(unit, number);
            displayText = `${text} (${displayUnit})`;
        }
        tag.textContent = displayText;

        const close = document.createElement('div');
        close.className = 'cursor-pointer';
        close.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M10.5 3.5L3.5 10.5" stroke="#3B3B3B" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.5 3.5L3.5 10.5" stroke="white" stroke-opacity="0.2" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3.5 3.5L10.5 10.5" stroke="#3B3B3B" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3.5 3.5L10.5 10.5" stroke="white" stroke-opacity="0.2" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        `;
        close.onclick = () => {
            container.removeChild(tag);
            tags = tags.filter((t) => t !== text);
            updateHiddenInput();
        };

        tag.appendChild(close);
        container.insertBefore(tag, input);
    };

    const createTag = (text) => {
        if (!/^\d+$/.test(text)) return; // Дозволяємо лише цілі числа
        if (tags.includes(text)) return;

        tags.push(text);
        renderTag(text);
        updateHiddenInput();
    };

    const addTag = () => {
        const text = input.value.trim();
        createTag(text);
        input.value = '';
    };

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTag();
        }
    });

    input.addEventListener('blur', () => {
        addTag();
    });

    // Очистити DOM тегів перед рендером
    container.querySelectorAll('.tag').forEach((tagEl) => tagEl.remove());

    // Відобразити теги
    tags.forEach((tag) => renderTag(tag));

    updateHiddenInput();
});
