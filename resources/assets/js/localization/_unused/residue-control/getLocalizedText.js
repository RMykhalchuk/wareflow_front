import { localizationJSON } from './local_residue-control_JSON.js';

export function getLocalizedText(key) {
    const selectedLanguage = localStorage.getItem('Language');
    return localizationJSON[selectedLanguage][key];
}
