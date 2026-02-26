// Функція для отримання координат із Google Link або прямого вводу "lat, lng"
export function getCoordinatesFromInput(input) {
    try {
        input = input.trim();

        if (input.includes('maps.app.goo.gl')) {
            return { error: 'short_link' };
        }

        // 1) Спробувати центр карти (@lat,lng)
        const regexAt = /@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)(?:,[0-9.]+[a-z]*)?/i;
        let match = input.match(regexAt);
        if (match) {
            return L.latLng(parseFloat(match[1]), parseFloat(match[2]));
        }

        // 2) Всі !3d…!4d… → беремо останні (якщо є)
        const regexBangGlobal = /!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/g;
        let lastMatch = null;
        let m;
        while ((m = regexBangGlobal.exec(input)) !== null) {
            lastMatch = m;
        }
        if (lastMatch) {
            return L.latLng(parseFloat(lastMatch[1]), parseFloat(lastMatch[2]));
        }

        // 3) Якщо введено напряму "lat, lng"
        const regexCoords = /^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)\s*$/;
        match = input.match(regexCoords);
        if (match) {
            return L.latLng(parseFloat(match[1]), parseFloat(match[2]));
        }

        // Якщо нічого не підійшло
        return null;
        // return { error: 'invalid_format' };
    } catch (e) {
        return null;
    }
}
