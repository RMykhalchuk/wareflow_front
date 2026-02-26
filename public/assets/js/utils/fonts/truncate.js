export function truncate(text, limit = 25) {
    if (!text) return '-';
    return text.length > limit ? text.slice(0, limit) + '…' : text;
}
