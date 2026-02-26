// 1. Якщо мова ще не збережена – зберігаємо поточну (що прийшла з Laravel)

// ==== 2. Визначаємо мову з URL ====
const path = window.location.pathname;

// Поточна мова з URL (uk / en / de / pl)
let urlLang = null;

if (path.startsWith('/uk')) urlLang = 'uk';
else if (path.startsWith('/de')) urlLang = 'de';
else if (path.startsWith('/pl')) urlLang = 'pl';
else urlLang = 'en'; // усе інше → англійська

// ==== 3. Мова, яка вибрана користувачем ====
const storedLang = localStorage.getItem('Language') || 'en';

// ==== 4. Якщо URL ≠ збережена мова → редірект ====
if (storedLang !== urlLang) {
    let redirectPrefix = '/en'; // default

    if (storedLang === 'uk') redirectPrefix = '/uk';
    if (storedLang === 'de') redirectPrefix = '/de';
    if (storedLang === 'pl') redirectPrefix = '/pl';

    // Перенаправляємо на правильну мову
    const newUrl = redirectPrefix + window.location.pathname.replace(/^\/(uk|de|pl|en)/, '');

    window.location.replace(newUrl);
}

// ==== 5. Функція оновлення dropdown ====
function applyLanguage(lang) {
    document.querySelectorAll('.dropdown-language').forEach((drop) => {
        const flag = drop.querySelector('.flag-icon');
        const text = drop.querySelector('.selected-language');

        if (!flag || !text) return;

        if (lang === 'uk') {
            flag.className = 'flag-icon flag-icon-ua';
            text.textContent = 'Українська';
            text.setAttribute('data-i18n', 'LangTitleUA');
        } else if (lang === 'de') {
            flag.className = 'flag-icon flag-icon-de';
            text.textContent = 'Deutsch';
            text.setAttribute('data-i18n', 'LangTitleGE');
        } else if (lang === 'pl') {
            flag.className = 'flag-icon flag-icon-pl';
            text.textContent = 'Polski';
            text.setAttribute('data-i18n', 'LangTitlePL');
        } else {
            flag.className = 'flag-icon flag-icon-us';
            text.textContent = 'English';
            text.setAttribute('data-i18n', 'LangTitleEN');
        }
    });
}

// ==== 6. Застосовуємо мову ====
applyLanguage(storedLang);

// ==== 7. Синхронізуємо ====
localStorage.setItem('Language', storedLang);
