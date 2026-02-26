export async function sendRequest(url, uri, method, data = '') {
    const apiUrl = url + uri;
    const requestOptions = {
        method: method,
        body: data,
    };

    // Повертаємо Promise з функції
    return await fetch(apiUrl, requestOptions).then((response) => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    });
}

export async function sendResponseActionEdit(url, uri, method = 'PUT', data = '') {
    const apiUrl = url + uri;
    const requestOptions = {
        method: method,
        body: data,
    };

    // Повертаємо Promise з функції
    return await fetch(apiUrl, requestOptions).then((response) => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    });
}

// Функція для отримання даних
export async function getResponse(url, uri, method = 'GET', data) {
    try {
        const apiUrl = url + uri;
        const requestOptions = {
            method: method,
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
        };

        const response = await fetch(apiUrl, requestOptions);
        data = await response.json();

        return data;
    } catch (error) {
        console.error('Error:', error);
        // Обробити помилку тут
        throw error; // Звернення помилки для подальшого оброблення
    }
}
