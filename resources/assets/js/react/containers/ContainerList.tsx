import React, { useState, useEffect, useCallback } from 'react';
import ContainerTable from './ContainerTable';
import ContainerFilters from './ContainerFilters';
import LoadingSpinner from '../components/LoadingSpinner';
import ErrorAlert from '../components/ErrorAlert';
import { ContainerApi } from './api/containerApi';
import { getTranslations } from './localization';
import type { Container, ContainerFilters as Filters, ContainerAppProps } from '../types/container';

const ContainerList: React.FC<ContainerAppProps> = ({ locale, apiBaseUrl }) => {
  const [containers, setContainers] = useState<Container[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalRecords, setTotalRecords] = useState(0);
  const [perPage, setPerPage] = useState(10);
  const [filters, setFilters] = useState<Filters>({
    search: '',
    type: '',
    reversible: '',
  });

  const t = getTranslations(locale);
  const api = new ContainerApi(apiBaseUrl, locale);

  const fetchContainers = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await api.fetchContainers(currentPage, perPage, filters);
      setContainers(response.data);
      setTotalRecords(response.total);
      setTotalPages(Math.ceil(response.total / perPage));
    } catch (err) {
      setError(err instanceof Error ? err.message : t.error);
      setContainers([]);
    } finally {
      setLoading(false);
    }
  }, [currentPage, perPage, filters, api, t.error]);

  useEffect(() => {
    fetchContainers();
  }, [fetchContainers]);

  const handleFiltersChange = (newFilters: Filters) => {
    setFilters(newFilters);
    setCurrentPage(1);
  };

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handlePerPageChange = (newPerPage: number) => {
    setPerPage(newPerPage);
    setCurrentPage(1);
  };

  if (loading && containers.length === 0) {
    return <LoadingSpinner message={t.loading} />;
  }

  return (
    <div className="space-y-6">
      {error && <ErrorAlert error={error} title={t.error} />}

      <ContainerFilters
        filters={filters}
        onFiltersChange={handleFiltersChange}
        translations={t}
      />

      {loading && containers.length > 0 ? (
        <div className="relative">
          <div className="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
            <LoadingSpinner size="sm" message="" />
          </div>
          <ContainerTable
            data={containers}
            translations={t}
            apiBaseUrl={apiBaseUrl}
            locale={locale}
            onPageChange={handlePageChange}
            currentPage={currentPage}
            totalPages={totalPages}
            totalRecords={totalRecords}
            perPage={perPage}
            onPerPageChange={handlePerPageChange}
          />
        </div>
      ) : (
        <ContainerTable
          data={containers}
          translations={t}
          apiBaseUrl={apiBaseUrl}
          locale={locale}
          onPageChange={handlePageChange}
          currentPage={currentPage}
          totalPages={totalPages}
          totalRecords={totalRecords}
          perPage={perPage}
          onPerPageChange={handlePerPageChange}
        />
      )}
    </div>
  );
};

export default ContainerList;
