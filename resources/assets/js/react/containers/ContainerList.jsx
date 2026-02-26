import React, { useState, useEffect } from 'react';
import ContainerTable from './ContainerTable';
import ContainerFilters from './ContainerFilters';
import ContainerPagination from './ContainerPagination';
import LoadingSpinner from '../components/LoadingSpinner';
import ErrorAlert from '../components/ErrorAlert';
import { fetchContainers } from './api/containerApi';

const ContainerList = ({ locale = 'uk', apiBaseUrl = '' }) => {
    const [containers, setContainers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [filterType, setFilterType] = useState('');
    const [filterReversible, setFilterReversible] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [totalRecords, setTotalRecords] = useState(0);

    const itemsPerPage = 10;
    const languageBlock = locale === 'en' ? '' : `/${locale}`;

    useEffect(() => {
        loadContainers();
    }, [currentPage, searchTerm, filterType, filterReversible]);

    const loadContainers = async () => {
        try {
            setLoading(true);

            const params = {
                page: currentPage,
                per_page: itemsPerPage,
                search: searchTerm,
                type: filterType,
                reversible: filterReversible,
            };

            const data = await fetchContainers(apiBaseUrl, languageBlock, params);

            setContainers(data.data || []);
            setTotalRecords(data.total || 0);
            setTotalPages(Math.ceil((data.total || 0) / itemsPerPage));
            setError(null);
        } catch (err) {
            setError(err.message);
            setContainers([]);
        } finally {
            setLoading(false);
        }
    };

    const handleSearchChange = (value) => {
        setSearchTerm(value);
        setCurrentPage(1);
    };

    const handleTypeFilter = (value) => {
        setFilterType(value);
        setCurrentPage(1);
    };

    const handleReversibleFilter = (value) => {
        setFilterReversible(value);
        setCurrentPage(1);
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    if (loading && containers.length === 0) {
        return <LoadingSpinner locale={locale} />;
    }

    if (error) {
        return <ErrorAlert error={error} locale={locale} />;
    }

    return (
        <div className="container-list-widget">
            <ContainerFilters
                locale={locale}
                searchTerm={searchTerm}
                filterType={filterType}
                filterReversible={filterReversible}
                onSearchChange={handleSearchChange}
                onTypeChange={handleTypeFilter}
                onReversibleChange={handleReversibleFilter}
            />

            <ContainerTable
                locale={locale}
                apiBaseUrl={apiBaseUrl}
                languageBlock={languageBlock}
                containers={containers}
                loading={loading}
                searchTerm={searchTerm}
                filterType={filterType}
                filterReversible={filterReversible}
            />

            {totalPages > 1 && (
                <ContainerPagination
                    currentPage={currentPage}
                    totalPages={totalPages}
                    onPageChange={handlePageChange}
                />
            )}
        </div>
    );
};

export default ContainerList;
