document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('task-list');

    function updateIndexes() {
        list.querySelectorAll('.task-index').forEach((el, i) => {
            el.textContent = i + 1;
        });
    }

    list.addEventListener('click', function (e) {
        const trigger = e.target.closest('.task-move-trigger');
        if (!trigger) return;

        // Не реагуємо на клік по світчу
        if (e.target.matches('input[type="checkbox"]')) return;

        const item = trigger.closest('[data-original]');
        const fixedPos = parseInt(item.dataset.fixed || '', 10);
        const iconContainer = item.querySelector('.task-icon');

        if (!fixedPos) return;

        // Функція для оновлення іконки
        function setIcon(up) {
            if (!iconContainer) return;
            iconContainer.innerHTML = `<i data-feather="${up ? 'arrow-up' : 'arrow-down'}"></i>`;
            feather.replace();
        }

        if (item.dataset.isFixed === '1') {
            // Розфіксовуємо — повертаємо по original
            const original = parseInt(item.dataset.original, 10);
            item.remove();

            let inserted = false;
            for (const sibling of list.children) {
                const siblingOriginal = parseInt(sibling.dataset.original, 10);
                if (siblingOriginal > original) {
                    list.insertBefore(item, sibling);
                    inserted = true;
                    break;
                }
            }
            if (!inserted) list.appendChild(item);

            item.dataset.isFixed = '0';
            setIcon(true); // стрілка вгору
            updateIndexes();
            return;
        }

        // Ще не закріплений — зберігаємо current position
        item.dataset.prevAfterOriginal = item.nextElementSibling?.dataset.original || '';

        // Рухаємо на фіксовану позицію
        item.remove();
        const targetIndex = Math.max(0, fixedPos - 1);
        if (targetIndex >= list.children.length) {
            list.appendChild(item);
        } else {
            list.insertBefore(item, list.children[targetIndex]);
        }

        item.dataset.isFixed = '1';
        setIcon(false); // стрілка вниз
        updateIndexes();
    });
});
