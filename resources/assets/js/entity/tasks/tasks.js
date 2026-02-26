import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    const taskId = $('#task-container').data('id');

    // --- створення ---
    $('#create').click(async function () {
        let formData = getData();

        await sendRequestBase(`${url}tasks`, formData, validate, function () {
            redirectWithLocale('/tasks');
        });
    });

    // --- редагування ---
    $('#edit').click(async function () {
        let formData = getData();
        formData.append('_method', 'PUT');

        await sendRequestBase(`${url}tasks/${container.id}`, formData, validate, function () {
            redirectWithLocale('/tasks');
        });
    });

    // --- Скасувати ---
    $('#cancelTask').click(async function () {
        let formData = getDataCancel();

        await sendRequestBase(`${url}tasks/${taskId}/cancel`, formData, null, function () {
            redirectWithLocale(`/tasks/${taskId}`);
        });
    });

    // --- пріоритет ---
    let currentPriority = $('.priority-btn.active').data('value');

    // клік по кнопці пріоритету
    $('#priority_full_dropdown').on('click', '.priority-btn', async function () {
        const $this = $(this);
        const newPriority = $this.data('value');

        // якщо натиснули той самий — нічого не робимо
        if (newPriority === currentPriority) return;

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const formData = new FormData();
        formData.append('_token', csrf);
        formData.append('_method', 'PATCH');

        await sendRequestBase(
            `${url}tasks/${taskId}/priority/${newPriority}`,
            formData,
            validatePriorityResponse,
            function () {
                console.log(`Priority successfully updated to ${newPriority}`);
                currentPriority = newPriority;
            }
        );
    });

    // --- додавання виконавця ---
    $('#submit_executors').click(async function () {
        const executorId = $('#executor_user_id').val();

        if (!executorId) {
            $('#message-executors').html(
                `<div class="text-danger small">Будь ласка, виберіть виконавця.</div>`
            );
            return;
        }

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const formData = new FormData();
        formData.append('_token', csrf);

        await sendRequestBase(
            `${url}tasks/${taskId}/executors/${executorId}`,
            formData,
            validateExecutorsResponse,
            function () {
                // При успішному додаванні
                $('#message-executors').html(
                    `<div class="text-success small">✅ Виконавця успішно додано.</div>`
                );

                // Закрити модалку через Bootstrap
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById('addExecutorUsers')
                );
                modal.hide();

                // Після успіху — просто перезавантажити сторінку
                location.reload();

                // (опціонально) оновити список виконавців без перезавантаження
                reloadExecutorsList();
            }
        );
    });

    // --- видалення виконавця ---
    $(document).on('click', '.remove-executor', async function () {
        const $executorItem = $(this).closest('.executor-item');
        const executorId = $executorItem.data('executor-id');

        // if (!confirm('Ви впевнені, що хочете видалити цього виконавця?')) return;

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const formData = new FormData();
        formData.append('_token', csrf);
        formData.append('_method', 'DELETE');

        await sendRequestBase(
            `${url}tasks/${taskId}/executors/${executorId}`,
            formData,
            validateDeleteExecutorResponse,
            function () {
                // При успіху просто прибираємо елемент або перезавантажуємо сторінку
                $executorItem.fadeOut(200, function () {
                    $(this).remove();
                });

                // Якщо хочеш просто оновлювати все — розкоментуй це:
                location.reload();
            }
        );
    });

    // --- створення ---
    $('#send_to_work').click(async function () {
        let csrf = document.querySelector('meta[name="csrf-token"]').content;
        let formData = new FormData();
        formData.append('_token', csrf);

        await sendRequestBase(`${url}tasks/${taskId}/in-progress`, formData, null, function () {
            redirectWithLocale(`/tasks/${taskId}`);
        });
    });
});

// --- Допоміжні функції ---
function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);
    formData.append('field', $('#field').val());

    return formData;
}

async function validate(response) {
    const fieldGroups = [{ fields: ['field'], container: '#main-data-message_full' }];

    await validateGeneric(response, fieldGroups, '#main-data-message_full');
}

async function validatePriorityResponse(response) {
    const fieldGroups = [{ fields: ['priority'], container: '#main-data-message_full' }];
    await validateGeneric(response, fieldGroups, '#main-data-message_full');
}

async function validateExecutorsResponse(response) {
    const fieldGroups = [{ fields: ['executor_user_id'], container: '#message-executors' }];
    await validateGeneric(response, fieldGroups, '#message-executors');
}

// --- Валідація для delete ---
async function validateDeleteExecutorResponse(response) {
    const fieldGroups = [{ fields: ['executor'], container: '#main-data-message_full' }];
    await validateGeneric(response, fieldGroups, '#main-data-message_full');
}

// --- опціонально: оновлення списку виконавців ---
function reloadExecutorsList() {
    // наприклад, через AJAX або перерендер елементу
    console.log('Executors list reloaded');
}

function getDataCancel() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);
    return formData;
}
