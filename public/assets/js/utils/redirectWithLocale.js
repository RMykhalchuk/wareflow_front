import { getCurrentLocaleFromUrl } from './getCurrentLocaleFromUrl.js';

// ✅ Редірект з локаллю
export function redirectWithLocale(route = '/') {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `${window.location.origin}${route}` // без префіксу
            : `${window.location.origin}/${locale}${route}`;

    window.location.href = url;
}
