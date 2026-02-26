import React, { useState } from 'react';
import ContainerRow from './ContainerRow';
import { translations } from './localization';

const ContainerTable = ({
    locale,
    apiBaseUrl,
    languageBlock,
    containers,
    loading,
    searchTerm,
    filterType,
    filterReversible
}) => {
    const t = translations[locale] || translations.uk;

    const hasFilters = searchTerm || filterType || filterReversible;

    return (
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
                                {hasFilters ? t.noResults : t.noData}
                            </td>
                        </tr>
                    ) : (
                        containers.map((container) => (
                            <ContainerRow
                                key={container.id}
                                container={container}
                                locale={locale}
                                apiBaseUrl={apiBaseUrl}
                                languageBlock={languageBlock}
                            />
                        ))
                    )}
                </tbody>
            </table>
            {loading && containers.length > 0 && (
                <div className="text-center py-2">
                    <div className="spinner-border spinner-border-sm text-primary" role="status">
                        <span className="visually-hidden">{t.loading}</span>
                    </div>
                </div>
            )}
        </div>
    );
};

export default ContainerTable;
