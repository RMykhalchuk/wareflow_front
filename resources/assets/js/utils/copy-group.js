export function copyGroup(
    buttonSelector = 'button[id^="copy-button-"]',
    { getContentId = (btnId) => btnId.replace('button', 'content'), onCopied = null } = {}
) {
    document.querySelectorAll(buttonSelector).forEach((button) => {
        button.addEventListener('click', () => {
            const btnId = button.id;
            const contentId = getContentId(btnId);

            const contentElement = document.getElementById(contentId);
            if (!contentElement) return;

            const textToCopy = contentElement.innerText.trim();

            // Створення textarea для копіювання
            const textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            // Додатковий кастомний callback
            if (typeof onCopied === 'function') {
                onCopied(button, contentElement, textToCopy);
            }

            // Іконка → check (якщо є feather)
            // const icon = button.querySelector('i[data-feather]');
            // if (icon && typeof feather !== 'undefined') {
            //     icon.setAttribute('data-feather', 'check');
            //     feather.replace();
            //     setTimeout(() => {
            //         icon.setAttribute('data-feather', 'copy');
            //         feather.replace();
            //     }, 1000);
            // }
        });
    });
}
