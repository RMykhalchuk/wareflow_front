const WEAK_PASSWORD_MESSAGE = {
    uk: 'Пароль занадто простий. Використовуйте комбінацію літер і цифр та уникайте однакових символів.',
    en: 'The password is too simple. Use a combination of letters, numbers, and special symbols, and avoid repeating the same character.',
};

/**
 * Validates password strength.
 * Returns an error message string if invalid, or null if valid.
 *
 * Rules:
 * - Minimum 8 characters
 * - Cannot consist of identical characters (e.g. 11111111)
 * - Must contain: lowercase letter, uppercase letter, digit, special character
 *
 * @param {string} password
 * @param {string} locale - 'uk' or 'en'
 * @returns {string|null}
 */
export function validatePassword(password, locale = 'uk') {
    const msg = WEAK_PASSWORD_MESSAGE[locale] ?? WEAK_PASSWORD_MESSAGE['uk'];

    if (password.length < 8) {
        return msg;
    }

    if (/^(.)\1*$/.test(password)) {
        return msg;
    }

    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasDigit = /[0-9]/.test(password);
    const hasSymbol = /[^a-zA-Z0-9]/.test(password);

    if (!hasLower || !hasUpper || !hasDigit || !hasSymbol) {
        return msg;
    }

    return null;
}
