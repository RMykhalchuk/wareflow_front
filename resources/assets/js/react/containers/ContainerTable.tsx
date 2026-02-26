import React, { useMemo, useState, Fragment } from 'react';
import {
  useReactTable,
  getCoreRowModel,
  getSortedRowModel,
  getPaginationRowModel,
  flexRender,
  createColumnHelper,
  SortingState,
} from '@tanstack/react-table';
import { Menu, Transition } from '@headlessui/react';
import { EllipsisVerticalIcon } from '@heroicons/react/24/outline';
import { useIntl } from 'react-intl';
import { messages } from './messages';
import type { Container } from '../types/container';

interface ContainerTableProps {
  data: Container[];
  apiBaseUrl: string;
  locale: string;
  onPageChange: (page: number) => void;
  currentPage: number;
  totalPages: number;
  totalRecords: number;
  perPage: number;
  onPerPageChange: (perPage: number) => void;
}

const ContainerTable: React.FC<ContainerTableProps> = ({
  data,
  apiBaseUrl,
  locale,
  onPageChange,
  currentPage,
  totalPages,
  totalRecords,
  perPage,
  onPerPageChange,
}) => {
  const { formatMessage } = useIntl();
  const [sorting, setSorting] = useState<SortingState>([]);
  const languagePrefix = locale === 'en' ? '' : `/${locale}`;

  const columnHelper = createColumnHelper<Container>();

  const columns = useMemo(
    () => [
      columnHelper.accessor('local_id', {
        header: formatMessage(messages.id),
        cell: (info) => (
          <span className="text-gray-600 font-mono text-sm">
            {info.getValue()}
          </span>
        ),
        size: 80,
      }),
      columnHelper.accessor('name', {
        header: formatMessage(messages.name),
        cell: (info) => {
          const row = info.row.original;
          return (
            <div className="flex flex-col">
              <a
                href={`${apiBaseUrl}${languagePrefix}/containers/${row.id}`}
                className="text-gray-900 font-semibold hover:text-primary-600 transition-colors"
              >
                {info.getValue()}
              </a>
              <span className="text-gray-500 text-sm">{row.code_format}</span>
            </div>
          );
        },
      }),
      columnHelper.accessor('type', {
        header: formatMessage(messages.type),
        cell: (info) => {
          const typeMap: Record<string, string> = {
            'Тип 1': formatMessage(messages.type_1),
            'Тип 2': formatMessage(messages.type_2),
            'Тип 3': formatMessage(messages.type_3),
            'Тип 4': formatMessage(messages.type_4),
            'Тип 5': formatMessage(messages.type_5),
            'Тип 6': formatMessage(messages.type_6),
          };
          const value = info.getValue();
          return (
            <span className="text-gray-600">
              {typeMap[value] || value}
            </span>
          );
        },
        size: 200,
      }),
      columnHelper.accessor('reversible', {
        header: formatMessage(messages.reversible),
        cell: (info) => {
          const isReversible = info.getValue() === 1;
          return isReversible ? (
            <div className="flex items-center gap-2">
              <div className="w-2.5 h-2.5 bg-success-500 rounded-full" />
              <span className="font-semibold text-gray-900">{formatMessage(messages.yes)}</span>
            </div>
          ) : null;
        },
        size: 150,
      }),
      columnHelper.display({
        id: 'actions',
        header: () => <span className="text-center block">{formatMessage(messages.actions)}</span>,
        cell: ({ row }) => {
          const container = row.original;
          return (
            <div className="flex justify-center">
              <Menu as="div" className="relative inline-block text-left">
                <Menu.Button className="inline-flex justify-center items-center w-8 h-8 rounded-full hover:bg-gray-100 transition-colors">
                  <EllipsisVerticalIcon className="w-5 h-5 text-gray-600" />
                </Menu.Button>

                <Transition
                  as={Fragment}
                  enter="transition ease-out duration-100"
                  enterFrom="transform opacity-0 scale-95"
                  enterTo="transform opacity-100 scale-100"
                  leave="transition ease-in duration-75"
                  leaveFrom="transform opacity-100 scale-100"
                  leaveTo="transform opacity-0 scale-95"
                >
                  <Menu.Items className="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                    <div className="py-1">
                      <Menu.Item>
                        {({ active }) => (
                          <a
                            href={`${apiBaseUrl}${languagePrefix}/containers/${container.id}`}
                            className={`${
                              active ? 'bg-gray-100' : ''
                            } block px-4 py-2 text-sm text-gray-700`}
                          >
                            {formatMessage(messages.view)}
                          </a>
                        )}
                      </Menu.Item>
                      <Menu.Item>
                        {({ active }) => (
                          <a
                            href={`${apiBaseUrl}${languagePrefix}/containers/${container.id}/edit`}
                            className={`${
                              active ? 'bg-gray-100' : ''
                            } block px-4 py-2 text-sm text-gray-700`}
                          >
                            {formatMessage(messages.edit)}
                          </a>
                        )}
                      </Menu.Item>
                    </div>
                  </Menu.Items>
                </Transition>
              </Menu>
            </div>
          );
        },
        size: 70,
      }),
    ],
    [formatMessage, apiBaseUrl, languagePrefix, columnHelper]
  );

  const table = useReactTable({
    data,
    columns,
    state: {
      sorting,
    },
    onSortingChange: setSorting,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    manualPagination: true,
    pageCount: totalPages,
  });

  const startRecord = (currentPage - 1) * perPage + 1;
  const endRecord = Math.min(currentPage * perPage, totalRecords);

  return (
    <div>
      <div className="overflow-x-auto border border-gray-200 rounded-lg">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            {table.getHeaderGroups().map((headerGroup) => (
              <tr key={headerGroup.id}>
                {headerGroup.headers.map((header) => (
                  <th
                    key={header.id}
                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    onClick={header.column.getToggleSortingHandler()}
                    style={{ width: header.column.getSize() }}
                  >
                    <div className="flex items-center gap-2">
                      {flexRender(
                        header.column.columnDef.header,
                        header.getContext()
                      )}
                      {header.column.getIsSorted() && (
                        <span>
                          {header.column.getIsSorted() === 'asc' ? '↑' : '↓'}
                        </span>
                      )}
                    </div>
                  </th>
                ))}
              </tr>
            ))}
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {data.length === 0 ? (
              <tr>
                <td
                  colSpan={columns.length}
                  className="px-6 py-12 text-center text-gray-500"
                >
                  {formatMessage(messages.noResults)}
                </td>
              </tr>
            ) : (
              table.getRowModel().rows.map((row) => (
                <tr
                  key={row.id}
                  className="hover:bg-gray-50 transition-colors"
                >
                  {row.getVisibleCells().map((cell) => (
                    <td key={cell.id} className="px-6 py-4 whitespace-nowrap">
                      {flexRender(
                        cell.column.columnDef.cell,
                        cell.getContext()
                      )}
                    </td>
                  ))}
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {totalPages > 1 && (
        <div className="flex items-center justify-between mt-4">
          <div className="flex items-center gap-4">
            <span className="text-sm text-gray-700">
              {formatMessage(messages.showing)} <span className="font-medium">{startRecord}</span> - <span className="font-medium">{endRecord}</span> {formatMessage(messages.of)} <span className="font-medium">{totalRecords}</span> {formatMessage(messages.results)}
            </span>
            <select
              value={perPage}
              onChange={(e) => onPerPageChange(Number(e.target.value))}
              className="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
            >
              <option value={10}>10 {formatMessage(messages.rowsPerPage)}</option>
              <option value={25}>25 {formatMessage(messages.rowsPerPage)}</option>
              <option value={50}>50 {formatMessage(messages.rowsPerPage)}</option>
              <option value={100}>100 {formatMessage(messages.rowsPerPage)}</option>
            </select>
          </div>

          <nav className="flex items-center gap-2">
            <button
              onClick={() => onPageChange(currentPage - 1)}
              disabled={currentPage === 1}
              className="px-3 py-2 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {formatMessage(messages.previous)}
            </button>

            {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
              let pageNum;
              if (totalPages <= 5) {
                pageNum = i + 1;
              } else if (currentPage <= 3) {
                pageNum = i + 1;
              } else if (currentPage >= totalPages - 2) {
                pageNum = totalPages - 4 + i;
              } else {
                pageNum = currentPage - 2 + i;
              }

              return (
                <button
                  key={pageNum}
                  onClick={() => onPageChange(pageNum)}
                  className={`px-3 py-2 rounded-md text-sm font-medium ${
                    currentPage === pageNum
                      ? 'bg-primary-600 text-white'
                      : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
                  }`}
                >
                  {pageNum}
                </button>
              );
            })}

            <button
              onClick={() => onPageChange(currentPage + 1)}
              disabled={currentPage === totalPages}
              className="px-3 py-2 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {formatMessage(messages.next)}
            </button>
          </nav>
        </div>
      )}
    </div>
  );
};

export default ContainerTable;
