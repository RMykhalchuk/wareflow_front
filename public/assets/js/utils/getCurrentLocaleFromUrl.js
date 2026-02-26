// 🔄 Визначаємо локаль з URL
export const getCurrentLocaleFromUrl = () => {
    const path = window.location.pathname;
    const firstSegment = path.split('/')[1];

    // якщо перший сегмент є і він відповідає підтримуваним локалям
    const supportedLocales = ['uk', 'pl', 'de']; // додай, якщо треба
    if (supportedLocales.includes(firstSegment)) {
        return firstSegment;
    }

    // якщо немає локалі — вважаємо, що англійська
    return 'en';
};
