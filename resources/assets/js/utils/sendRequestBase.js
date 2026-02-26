export async function sendRequestBase(url, data, validateFunction, callback) {
    const urlBase = window.location.origin;

    try {
        const response = await fetch(`${urlBase}/${url}`, {
            method: 'POST',
            body: data,
        });
        //TODO update this cond because it always use POST, make it configurable for PATCH, PUT

        // Перевірка будь-якого статусу 2XX
        if (response.ok) {
            // response.ok == true для статусів 200–299
            const contentType = response.headers.get('content-type');
            let resData;
            if (contentType && contentType.includes('application/json')) {
                resData = await response.json();
            }
            if (callback) callback(resData);
            return true;
        } else {
            await validateFunction(response);
        }
    } catch (error) {
        console.error('Помилка при запиті', error);
    }
}

export async function sendRequestBaseWithMethod(
    url,
    data,
    validateFunction,
    callback,
    method = 'POST'
) {
    const response = await fetch(url, {
        method: method,
        body: data,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
        },
        credentials: 'same-origin',
    });

    if (response.ok) {
        const json = await response.json();
        callback?.(json);
    } else {
        validateFunction?.(response);
    }
}
