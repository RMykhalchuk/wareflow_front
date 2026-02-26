import { appendAlert } from './appendAlert.js';

export async function sendRequest(uri, formData, selector, callback) {
    let url = window.location.origin;

    try {
        const response = await fetch(url + uri, {
            method: 'POST',
            body: formData,
        });

        if (response.status === 200 || response.status === 201) {
            if (callback) callback();
            return true;
        } else {
            const data = await response.json();
            if (selector) {
                appendAlert(selector, 'danger', Object.values(data.errors)[0]);
            }
            return false;
        }
    } catch (error) {
        console.error('Send request error:', error);
        if (selector) {
            appendAlert(selector, 'danger', 'Сталася помилка при з’єднанні з сервером');
        }
        return false;
    }
}
