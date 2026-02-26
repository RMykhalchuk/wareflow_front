import React from 'react';
import { translations } from './localization';

const ContainerFilters = ({
    locale,
    searchTerm,
    filterType,
    filterReversible,
    onSearchChange,
    onTypeChange,
    onReversibleChange,
}) => {
    const t = translations[locale] || translations.uk;

    return (
        <div className="row mb-2">
            <div className="col-md-4 mb-1">
                <input
                    type="text"
                    className="form-control"
                    placeholder={t.search}
                    value={searchTerm}
                    onChange={(e) => onSearchChange(e.target.value)}
                />
            </div>
            <div className="col-md-4 mb-1">
                <select
                    className="form-select"
                    value={filterType}
                    onChange={(e) => onTypeChange(e.target.value)}
                >
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
                    onChange={(e) => onReversibleChange(e.target.value)}
                >
                    <option value="">{t.filterByReversible} - {t.all}</option>
                    <option value="1">{t.yes}</option>
                    <option value="0">{t.no}</option>
                </select>
            </div>
        </div>
    );
};

export default ContainerFilters;
