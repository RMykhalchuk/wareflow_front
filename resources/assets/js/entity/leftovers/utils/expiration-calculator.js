function parseDate(v) {
    return v ? new Date(v) : null;
}
function formatDate(d) {
    return d
        ? `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
        : '';
}

function calculateAll(manufactureId, bbId, termId) {
    const m = document.getElementById(manufactureId);
    const b = document.getElementById(bbId);
    const t = document.getElementById(termId);

    const manufacture = parseDate(m.value);
    const bb = parseDate(b.value);
    const term = t.value ? parseInt(t.value) : null;

    const changedManufacture = m.dataset.userInput === 'true';
    const changedBb = b.dataset.userInput === 'true';
    const changedTerm = t.dataset.userInput === 'true';

    // -------------------------------
    // 1) Якщо змінили manufacture -> рахуємо BB
    // -------------------------------
    if (manufacture && term && changedManufacture) {
        const newBb = new Date(manufacture);
        newBb.setDate(newBb.getDate() + term);
        b.value = formatDate(newBb);
    }

    // -------------------------------
    // 2) Якщо змінили BB -> рахуємо manufacture
    // -------------------------------
    if (bb && term && changedBb) {
        const newM = new Date(bb);
        newM.setDate(newM.getDate() - term);
        m.value = formatDate(newM);
    }

    // -------------------------------
    // 3) Якщо змінили термін і є обидві дати -> перераховуємо BB
    // -------------------------------
    if (manufacture && bb && term && changedTerm) {
        const newBb = new Date(manufacture);
        newBb.setDate(newBb.getDate() + term);
        b.value = formatDate(newBb);
    }

    // -------------------------------
    // очищаємо прапорці після виконання
    // -------------------------------
    delete m.dataset.userInput;
    delete b.dataset.userInput;
    delete t.dataset.userInput;
}

// Ініціалізація слухачів
function initCalc(m, b, t) {
    [m, b, t].forEach((id) => {
        const el = document.getElementById(id);
        if (!el) return;

        if (el.tagName === 'SELECT') {
            // Стандартний change для простих селектів
            el.addEventListener('change', () => {
                el.dataset.userInput = 'true';
                calculateAll(m, b, t);
            });

            // Додатково для Select2
            if ($(el).hasClass('select2-hidden-accessible')) {
                $(el).on('select2:select', () => {
                    el.dataset.userInput = 'true';
                    calculateAll(m, b, t);
                });
            }
        } else {
            el.addEventListener('input', () => {
                el.dataset.userInput = 'true';
                calculateAll(m, b, t);
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initCalc('manufacture_date', 'bb_date', 'expiration_term');
    initCalc('edit_manufacture_date', 'edit_bb_date', 'edit_expiration_term');
});
