import React from 'react';
import { translations } from '../containers/localization';

const ErrorAlert = ({ error, locale = 'uk' }) => {
    const t = translations[locale] || translations.uk;

    return (
        <div className="card">
            <div className="card-body">
                <div className="alert alert-danger" role="alert">
                    <h4 className="alert-heading">{t.error}</h4>
                    <p className="mb-0">{error}</p>
                </div>
            </div>
        </div>
    );
};

export default ErrorAlert;
