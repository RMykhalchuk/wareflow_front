export function getUrlType() {
    const document_type = $('#document-container').data('type'); // "arrival", "outcome", "inner", "neutral"

    const typeToUrlMap = {
        arrival: 'income',
        outcome: 'outcome',
        inner: 'inner',
        neutral: 'neutral',
    };

    const urlType = typeToUrlMap[document_type] || 'income';

    console.log(`Тип документа: ${document_type} → URL: ${urlType}`);
    return urlType;
}
