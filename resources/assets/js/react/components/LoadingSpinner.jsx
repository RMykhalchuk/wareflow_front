import React from 'react';
import { translations } from '../containers/localization';

const LoadingSpinner = ({ locale = 'uk' }) => {
    const t = translations[locale] || translations.uk;

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
};

export default LoadingSpinner;
