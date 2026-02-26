export function calculateRowsInTabs(table, tabTypes, baseUrl, selectorName) {
    // Перевірка, чи було передано значення для tabTypes та baseUrl
    if (!tabTypes || !baseUrl) {
        console.error('tabTypes and baseUrl must be provided.');
        return;
    }

    // Перебираємо всі типи вкладок
    tabTypes.forEach((tabType) => {
        let url = `${baseUrl}${tabType}`;

        fetch(url)
            .then((response) => response.json())
            .then((totalData) => {
                // Отримання значення для відповідного селектора
                let value = totalData.total;
                // Знайдення відповідного селектора для вставки даних
                let selectorId = `${selectorName}${tabType}`;
                let element = document.getElementById(selectorId);
                // Вставка отриманого значення в селектор
                if (element) {
                    element.textContent = value;
                } else {
                    console.error(`Element with id ${selectorId} not found.`);
                }
            })
            .catch((error) => {
                console.error(`Error fetching data for ${tabType}:`, error);
            });
    });
}
