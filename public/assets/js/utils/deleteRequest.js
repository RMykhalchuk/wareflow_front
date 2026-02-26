import { appendAlert } from './appendAlert.js';

export async function sendDeleteRequest(uri, csrf, selector, callback) {
    let url = window.location.origin;

    try {
        const response = await fetch(url + uri, {
            method: 'DELETE',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
        });

        if (response.ok) {
            if (callback) callback();
            return true;
        } else {
            const data = await response.json().catch(() => null);
            if (data && data.errors && selector) {
                appendAlert(selector, 'danger', Object.values(data.errors)[0]);
            }
            return false;
        }
    } catch (error) {
        console.error('Delete request error:', error);
        if (selector) {
            appendAlert(selector, 'danger', 'Сталася помилка при з’єднанні з сервером');
        }
        return false;
    }
}
