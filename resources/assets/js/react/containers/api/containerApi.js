export const fetchContainers = async (apiBaseUrl, languageBlock, params) => {
    const queryString = new URLSearchParams(
        Object.entries(params).filter(([_, value]) => value !== '')
    ).toString();

    const url = `${apiBaseUrl}${languageBlock}/containers/api/list?${queryString}`;

    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
};

export const getContainer = async (apiBaseUrl, languageBlock, id) => {
    const url = `${apiBaseUrl}${languageBlock}/containers/api/${id}`;

    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
};

export const createContainer = async (apiBaseUrl, languageBlock, data) => {
    const url = `${apiBaseUrl}${languageBlock}/containers/api`;

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
};

export const updateContainer = async (apiBaseUrl, languageBlock, id, data) => {
    const url = `${apiBaseUrl}${languageBlock}/containers/api/${id}`;

    const response = await fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
};

export const deleteContainer = async (apiBaseUrl, languageBlock, id) => {
    const url = `${apiBaseUrl}${languageBlock}/containers/api/${id}`;

    const response = await fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
};
