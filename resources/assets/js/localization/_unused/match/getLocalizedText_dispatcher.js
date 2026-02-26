import { localizationDisptatcherJSON } from './local_dispatcher_JSON.js';

export function getLocalizedTextDispatcher(key) {
    const selectedLanguage = localStorage.getItem('Language');
    return localizationDisptatcherJSON[selectedLanguage][key];
}
