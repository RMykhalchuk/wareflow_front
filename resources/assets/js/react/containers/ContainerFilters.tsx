import React from 'react';
import Select from '../components/Select';
import type { ContainerFilters as Filters } from '../types/container';
import type { Translations } from './localization';

interface ContainerFiltersProps {
  filters: Filters;
  onFiltersChange: (filters: Filters) => void;
  translations: Translations;
}

const ContainerFilters: React.FC<ContainerFiltersProps> = ({
  filters,
  onFiltersChange,
  translations: t,
}) => {
  const typeOptions = [
    { value: '', label: t.all },
    { value: 'Тип 1', label: t.type_1 },
    { value: 'Тип 2', label: t.type_2 },
    { value: 'Тип 3', label: t.type_3 },
    { value: 'Тип 4', label: t.type_4 },
    { value: 'Тип 5', label: t.type_5 },
    { value: 'Тип 6', label: t.type_6 },
  ];

  const reversibleOptions = [
    { value: '', label: t.all },
    { value: '1', label: t.yes },
    { value: '0', label: t.no },
  ];

  return (
    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div>
        <label htmlFor="search" className="block text-sm font-medium text-gray-700 mb-1">
          {t.search}
        </label>
        <input
          id="search"
          type="text"
          value={filters.search}
          onChange={(e) => onFiltersChange({ ...filters, search: e.target.value })}
          placeholder={t.search}
          className="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
        />
      </div>

      <div>
        <label htmlFor="type-filter" className="block text-sm font-medium text-gray-700 mb-1">
          {t.filterByType}
        </label>
        <Select
          value={filters.type}
          onChange={(value) => onFiltersChange({ ...filters, type: value })}
          options={typeOptions}
          placeholder={t.all}
        />
      </div>

      <div>
        <label htmlFor="reversible-filter" className="block text-sm font-medium text-gray-700 mb-1">
          {t.filterByReversible}
        </label>
        <Select
          value={filters.reversible}
          onChange={(value) => onFiltersChange({ ...filters, reversible: value })}
          options={reversibleOptions}
          placeholder={t.all}
        />
      </div>
    </div>
  );
};

export default ContainerFilters;
