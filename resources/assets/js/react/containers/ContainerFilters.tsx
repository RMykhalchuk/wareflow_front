import React from 'react';
import { useIntl } from 'react-intl';
import Select from '../components/Select';
import { messages } from './messages';
import type { ContainerFilters as Filters } from '../types/container';

interface ContainerFiltersProps {
  filters: Filters;
  onFiltersChange: (filters: Filters) => void;
}

const ContainerFilters: React.FC<ContainerFiltersProps> = ({
  filters,
  onFiltersChange,
}) => {
  const { formatMessage } = useIntl();

  const typeOptions = [
    { value: '', label: formatMessage(messages.all) },
    { value: 'Тип 1', label: formatMessage(messages.type_1) },
    { value: 'Тип 2', label: formatMessage(messages.type_2) },
    { value: 'Тип 3', label: formatMessage(messages.type_3) },
    { value: 'Тип 4', label: formatMessage(messages.type_4) },
    { value: 'Тип 5', label: formatMessage(messages.type_5) },
    { value: 'Тип 6', label: formatMessage(messages.type_6) },
  ];

  const reversibleOptions = [
    { value: '', label: formatMessage(messages.all) },
    { value: '1', label: formatMessage(messages.yes) },
    { value: '0', label: formatMessage(messages.no) },
  ];

  return (
    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div>
        <label htmlFor="search" className="block text-sm font-medium text-gray-700 mb-1">
          {formatMessage(messages.search)}
        </label>
        <input
          id="search"
          type="text"
          value={filters.search}
          onChange={(e) => onFiltersChange({ ...filters, search: e.target.value })}
          placeholder={formatMessage(messages.search)}
          className="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
        />
      </div>

      <div>
        <label htmlFor="type-filter" className="block text-sm font-medium text-gray-700 mb-1">
          {formatMessage(messages.filterByType)}
        </label>
        <Select
          value={filters.type}
          onChange={(value) => onFiltersChange({ ...filters, type: value })}
          options={typeOptions}
          placeholder={formatMessage(messages.all)}
        />
      </div>

      <div>
        <label htmlFor="reversible-filter" className="block text-sm font-medium text-gray-700 mb-1">
          {formatMessage(messages.filterByReversible)}
        </label>
        <Select
          value={filters.reversible}
          onChange={(value) => onFiltersChange({ ...filters, reversible: value })}
          options={reversibleOptions}
          placeholder={formatMessage(messages.all)}
        />
      </div>
    </div>
  );
};

export default ContainerFilters;
