import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';

const ContainerWidget = ({ locale = 'uk', apiBaseUrl = '' }) => {
    const [containers, setContainers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [filterType, setFilterType] = useState('');
    const [filterReversible, setFilterReversible] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [popoverId, setPopoverId] = useState(null);

    const languageBlock = locale === 'en' ? '' : `/${locale}`;
    const itemsPerPage = 10;

    const translations = {
        uk: {
            title: 'Контейнери',
            addContainer: 'Додати контейнер',
            search: 'Пошук...',
            id: 'ID',
            name: 'Назва',
            code: 'Код',
            type: 'Тип',
            reversible: 'Реверсивний',
            actions: 'Дії',
            view: 'Переглянути',
            edit: 'Редагувати',
            loading: 'Завантаження...',
            error: 'Помилка завантаження даних',
            noData: 'Немає контейнерів',
            noResults: 'Результатів не знайдено',
            all: 'Всі',
            yes: 'Так',
            no: 'Ні',
            filterByType: 'Фільтр за типом',
            filterByReversible: 'Фільтр за реверсивністю',
            type_1: 'Тип 1',
            type_2: 'Тип 2',
            type_3: 'Тип 3',
            type_4: 'Тип 4',
            type_5: 'Тип 5',
            type_6: 'Тип 6',
        },
        en: {
            title: 'Containers',
            addContainer: 'Add Container',
            search: 'Search...',
            id: 'ID',
            name: 'Name',
            code: 'Code',
            type: 'Type',
            reversible: 'Reversible',
            actions: 'Actions',
            view: 'View',
            edit: 'Edit',
            loading: 'Loading...',
            error: 'Error loading data',
            noData: 'No containers',
            noResults: 'No results found',
            all: 'All',
            yes: 'Yes',
            no: 'No',
            filterByType: 'Filter by type',
            filterByReversible: 'Filter by reversibility',
            type_1: 'Type 1',
            type_2: 'Type 2',
            type_3: 'Type 3',
            type_4: 'Type 4',
            type_5: 'Type 5',
            type_6: 'Type 6',
        },
    };

    const t = translations[locale] || translations.uk;

    const typeTranslations = {
        'Тип 1': t.type_1,
        'Тип 2': t.type_2,
        'Тип 3': t.type_3,
        'Тип 4': t.type_4,
        'Тип 5': t.type_5,
        'Тип 6': t.type_6,
    };

    useEffect(() => {
        fetchContainers();
    }, [currentPage, searchTerm, filterType, filterReversible]);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (popoverId && !event.target.closest('.popover-menu')) {
                setPopoverId(null);
            }
        };

        document.addEventListener('click', handleClickOutside);
        return () => document.removeEventListener('click', handleClickOutside);
    }, [popoverId]);

    const fetchContainers = async () => {
        try {
            setLoading(true);
            const url = `${apiBaseUrl}${languageBlock}/containers/api/list`;

            const params = new URLSearchParams({
                page: currentPage,
                per_page: itemsPerPage,
                search: searchTerm,
                type: filterType,
                reversible: filterReversible,
            });

            const response = await fetch(`${url}?${params}`);

            if (!response.ok) {
                throw new Error('Failed to fetch containers');
            }

            const data = await response.json();
            setContainers(data.data || []);
            setTotalPages(Math.ceil((data.total || 0) / itemsPerPage));
            setError(null);
        } catch (err) {
            setError(err.message);
            setContainers([]);
        } finally {
            setLoading(false);
        }
    };

    const handleSearchChange = (e) => {
        setSearchTerm(e.target.value);
        setCurrentPage(1);
    };

    const handleTypeFilter = (e) => {
        setFilterType(e.target.value);
        setCurrentPage(1);
    };

    const handleReversibleFilter = (e) => {
        setFilterReversible(e.target.value);
        setCurrentPage(1);
    };

    const togglePopover = (id, e) => {
        e.stopPropagation();
        setPopoverId(popoverId === id ? null : id);
    };

    if (loading && containers.length === 0) {
        return (
            <div className="card">
                <div className="card-body text-center py-3">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">{t.loading}</span>
                    </div>
                    <p className="mt-2 mb-0">{t.loading}</p>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="card">
                <div className="card-body">
                    <div className="alert alert-danger" role="alert">
                        {t.error}: {error}
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="card">
            <div className="card-header d-flex justify-content-between align-items-center">
                <h4 className="card-title mb-0">{t.title}</h4>
                <a href={`${apiBaseUrl}${languageBlock}/containers/create`} className="btn btn-primary">
                    <i data-feather="plus" className="me-50"></i>
                    {t.addContainer}
                </a>
            </div>

            <div className="card-body">
                <div className="row mb-2">
                    <div className="col-md-4 mb-1">
                        <input
                            type="text"
                            className="form-control"
                            placeholder={t.search}
                            value={searchTerm}
                            onChange={handleSearchChange}
                        />
                    </div>
                    <div className="col-md-4 mb-1">
                        <select className="form-select" value={filterType} onChange={handleTypeFilter}>
                            <option value="">{t.filterByType} - {t.all}</option>
                            <option value="Тип 1">{t.type_1}</option>
                            <option value="Тип 2">{t.type_2}</option>
                            <option value="Тип 3">{t.type_3}</option>
                            <option value="Тип 4">{t.type_4}</option>
                            <option value="Тип 5">{t.type_5}</option>
                            <option value="Тип 6">{t.type_6}</option>
                        </select>
                    </div>
                    <div className="col-md-4 mb-1">
                        <select
                            className="form-select"
                            value={filterReversible}
                            onChange={handleReversibleFilter}
                        >
                            <option value="">{t.filterByReversible} - {t.all}</option>
                            <option value="1">{t.yes}</option>
                            <option value="0">{t.no}</option>
                        </select>
                    </div>
                </div>

                <div className="table-responsive">
                    <table className="table table-hover">
                        <thead>
                            <tr>
                                <th style={{ width: '80px' }}>{t.id}</th>
                                <th>{t.name}</th>
                                <th>{t.type}</th>
                                <th style={{ width: '150px' }}>{t.reversible}</th>
                                <th style={{ width: '70px', textAlign: 'center' }}>{t.actions}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {containers.length === 0 ? (
                                <tr>
                                    <td colSpan="5" className="text-center py-3">
                                        {searchTerm || filterType || filterReversible
                                            ? t.noResults
                                            : t.noData}
                                    </td>
                                </tr>
                            ) : (
                                containers.map((container) => (
                                    <tr key={container.id}>
                                        <td className="text-secondary">{container.local_id}</td>
                                        <td>
                                            <a
                                                href={`${apiBaseUrl}${languageBlock}/containers/${container.id}`}
                                                className="text-dark fw-bolder"
                                            >
                                                {container.name}
                                            </a>
                                            <br />
                                            <small className="text-muted">{container.code_format}</small>
                                        </td>
                                        <td className="text-secondary">
                                            {typeTranslations[container.type] || container.type}
                                        </td>
                                        <td>
                                            {container.reversible === 1 || container.reversible === '1' ? (
                                                <div className="d-flex align-items-center gap-50">
                                                    <div
                                                        className="bg-success"
                                                        style={{
                                                            width: '10px',
                                                            height: '10px',
                                                            borderRadius: '50%',
                                                        }}
                                                    ></div>
                                                    <span className="fw-bolder">{t.yes}</span>
                                                </div>
                                            ) : (
                                                ''
                                            )}
                                        </td>
                                        <td style={{ textAlign: 'center', position: 'relative' }}>
                                            <button
                                                className="btn btn-sm p-0 popover-menu"
                                                onClick={(e) => togglePopover(container.id, e)}
                                                style={{ background: 'none', border: 'none' }}
                                            >
                                                <svg
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <circle cx="12" cy="5" r="2" fill="currentColor" />
                                                    <circle cx="12" cy="12" r="2" fill="currentColor" />
                                                    <circle cx="12" cy="19" r="2" fill="currentColor" />
                                                </svg>
                                            </button>
                                            {popoverId === container.id && (
                                                <div
                                                    className="popover-menu"
                                                    style={{
                                                        position: 'absolute',
                                                        right: '30px',
                                                        top: '0',
                                                        backgroundColor: 'white',
                                                        border: '1px solid #ddd',
                                                        borderRadius: '4px',
                                                        boxShadow: '0 2px 8px rgba(0,0,0,0.15)',
                                                        zIndex: 1000,
                                                        minWidth: '150px',
                                                    }}
                                                >
                                                    <ul
                                                        style={{
                                                            listStyle: 'none',
                                                            margin: 0,
                                                            padding: '8px 0',
                                                        }}
                                                    >
                                                        <li>
                                                            <a
                                                                className="dropdown-item"
                                                                href={`${apiBaseUrl}${languageBlock}/containers/${container.id}`}
                                                                style={{
                                                                    padding: '8px 16px',
                                                                    display: 'block',
                                                                    color: '#333',
                                                                    textDecoration: 'none',
                                                                }}
                                                            >
                                                                {t.view}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a
                                                                className="dropdown-item"
                                                                href={`${apiBaseUrl}${languageBlock}/containers/${container.id}/edit`}
                                                                style={{
                                                                    padding: '8px 16px',
                                                                    display: 'block',
                                                                    color: '#333',
                                                                    textDecoration: 'none',
                                                                }}
                                                            >
                                                                {t.edit}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            )}
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {totalPages > 1 && (
                    <nav className="mt-3">
                        <ul className="pagination justify-content-center">
                            <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                                <button
                                    className="page-link"
                                    onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
                                    disabled={currentPage === 1}
                                >
                                    &laquo;
                                </button>
                            </li>
                            {[...Array(totalPages)].map((_, i) => (
                                <li
                                    key={i + 1}
                                    className={`page-item ${currentPage === i + 1 ? 'active' : ''}`}
                                >
                                    <button className="page-link" onClick={() => setCurrentPage(i + 1)}>
                                        {i + 1}
                                    </button>
                                </li>
                            ))}
                            <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                                <button
                                    className="page-link"
                                    onClick={() => setCurrentPage((p) => Math.min(totalPages, p + 1))}
                                    disabled={currentPage === totalPages}
                                >
                                    &raquo;
                                </button>
                            </li>
                        </ul>
                    </nav>
                )}
            </div>
        </div>
    );
};

if (typeof window !== 'undefined') {
    window.ContainerWidget = ContainerWidget;
    window.React = React;
    window.createRoot = createRoot;
}

export default ContainerWidget;
