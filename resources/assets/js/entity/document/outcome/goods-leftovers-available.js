// initGoodsAvailability.js
export function initGoodsAvailability({ selectId, inputId, hintId }) {
    const goodsSelect = $(selectId);
    const countInput = document.querySelector(inputId);
    const hint = document.querySelector(hintId);

    if (!goodsSelect.length || !countInput || !hint) return;

    let maxAvailable = null;
    let packageSize = null;
    let debounceTimer = null;

    function roundToPackage(value) {
        if (maxAvailable === null || packageSize === null || packageSize <= 0) return value;

        // округлення ВГОРУ до кратного
        let rounded = Math.ceil(value / packageSize) * packageSize;

        // якщо перевищили максимум — беремо максимально кратне ВНИЗ
        if (rounded > maxAvailable) {
            rounded = Math.floor(maxAvailable / packageSize) * packageSize;
        }

        return rounded > 0 ? rounded : packageSize;
    }

    function applyRounding() {
        if (maxAvailable === null || packageSize === null) return;

        let value = parseFloat(countInput.value.replace(',', '.'));
        if (isNaN(value) || value <= 0) return;

        const rounded = roundToPackage(value);
        if (rounded !== value) {
            countInput.value = rounded;
        }
    }

    function setInputDisabled(disabled) {
        countInput.disabled = disabled;

        if (disabled) {
            countInput.classList.add('bg-light');
        } else {
            countInput.classList.remove('bg-light');
        }
    }

    async function fetchAndShowAvailability(goodsId) {
        if (!goodsId) return;

        maxAvailable = null;
        packageSize = null;
        hint.classList.add('d-none');

        // дізейблимо поле поки чекаємо відповідь
        setInputDisabled(true);

        try {
            const response = await fetch(`/leftovers/available/${goodsId}`);
            const data = await response.json();

            maxAvailable = Number(data.count);
            packageSize = Number(data.quantity_in_package);

            hint.textContent = `Доступно: ${maxAvailable}, кратно: ${packageSize}`;
            hint.classList.remove('d-none');

            // якщо вже є значення — перевалідовуємо
            if (countInput.value) {
                applyRounding();
            }
        } catch (e) {
            hint.textContent = 'Не вдалося отримати залишки';
            hint.classList.remove('d-none');
        } finally {
            // розблоковуємо поле після отримання відповіді
            setInputDisabled(false);
            countInput.focus();
        }
    }

    // при зміні кількості — debounce, щоб не округляти моментально під час введення
    countInput.addEventListener('input', function () {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        debounceTimer = setTimeout(() => {
            applyRounding();
        }, 800);
    });

    // при втраті фокуса — одразу округлюємо (без очікування debounce)
    countInput.addEventListener('blur', function () {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
            debounceTimer = null;
        }

        applyRounding();
    });

    // ⭐ для edit: якщо значення вже встановлене програмно
    const currentValue = goodsSelect.val();
    if (currentValue) {
        fetchAndShowAvailability(currentValue);
    }

    // Можна повернути API, якщо треба буде чистити
    return {
        refetch: fetchAndShowAvailability,
    };
}
