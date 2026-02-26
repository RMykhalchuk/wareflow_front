import { appendAlert } from '../appendAlert.js';
import { getLocalizedText } from '../../localization/inventory/getLocalizedText.js';

export async function validateGeneric(response, fieldGroups, defaultContainer) {
    const res = await response.json();
    const errors = res.errors || {};

    const allMessages = {};
    // Підготувати масиви повідомлень для всіх контейнерів
    fieldGroups.forEach((group) => {
        allMessages[group.container] = [];
    });

    for (const [field, messages] of Object.entries(errors)) {
        const message = Array.isArray(messages) ? messages[0] : messages;

        let found = false;
        for (const group of fieldGroups) {
            const { fields, container, includePrefix } = group;

            if (fields.includes(field) || (includePrefix && field.startsWith(includePrefix))) {
                allMessages[container].push(message);
                found = true;
                break;
            }
        }

        if (!found) {
            const localizedField = getLocalizedText(field) || field;

            if (localizedField == 'company_id') {
                allMessages[defaultContainer].push(`${message}`);
            } else {
                allMessages[defaultContainer].push(
                    `${getLocalizedText('field') || 'Field'} "${localizedField}": ${message}`
                );
            }
        }
    }

    // Очистити попередні повідомлення
    for (const container in allMessages) {
        $(container).html('');
    }

    // Вивести повідомлення
    let anyShown = false;
    for (const [container, msgs] of Object.entries(allMessages)) {
        msgs.forEach((msg) => {
            appendAlert(container, 'danger', msg);
            anyShown = true;
        });
    }

    if (!anyShown) {
        appendAlert(
            defaultContainer,
            'danger',
            getLocalizedText('unknown_error') || 'Unknown error. Check input data.'
        );
    }
}
