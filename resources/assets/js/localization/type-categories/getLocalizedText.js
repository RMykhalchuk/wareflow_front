import { localizationJSON } from './localizationJSON.js';

export function getLocalizedText(key) {
    const selectedLanguage = localStorage.getItem('Language') || 'uk';
    const keys = key.split('.');
    let result = localizationJSON[selectedLanguage];

    for (let k of keys) {
        if (result && result.hasOwnProperty(k)) {
            result = result[k];
        } else {
            return key; // якщо не знайдено — повернути ключ для зручності дебагу
        }
    }
    return result;
}
