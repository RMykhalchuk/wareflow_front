export function switchLang() {
    // Отримуємо значення мови з локального сховища
    let language = localStorage.getItem('Language');

    // Встановлюємо мову за замовчуванням на англійську, якщо значення в localStorage не встановлено
    if (!language) {
        language = 'uk';
    } else if (language === 'uk') {
        language = 'uk';
    }
    return language;
}
