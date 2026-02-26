import React, { useState, useEffect, useRef } from 'react';
import { translations, typeTranslations } from './localization';

const ContainerRow = ({ container, locale, apiBaseUrl, languageBlock }) => {
    const [showPopover, setShowPopover] = useState(false);
    const popoverRef = useRef(null);
    const buttonRef = useRef(null);
    const t = translations[locale] || translations.uk;
    const typeT = typeTranslations[locale] || typeTranslations.uk;

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                popoverRef.current &&
                !popoverRef.current.contains(event.target) &&
                buttonRef.current &&
                !buttonRef.current.contains(event.target)
            ) {
                setShowPopover(false);
            }
        };

        if (showPopover) {
            document.addEventListener('mousedown', handleClickOutside);
        }

        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [showPopover]);

    const togglePopover = (e) => {
        e.stopPropagation();
        setShowPopover(!showPopover);
    };

    const translatedType = typeT[container.type] || container.type;
    const isReversible = container.reversible === 1 || container.reversible === '1';

    return (
        <tr>
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
            <td className="text-secondary">{translatedType}</td>
            <td>
                {isReversible && (
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
                )}
            </td>
            <td style={{ textAlign: 'center', position: 'relative' }}>
                <button
                    ref={buttonRef}
                    className="btn btn-sm p-0"
                    onClick={togglePopover}
                    style={{ background: 'none', border: 'none', cursor: 'pointer' }}
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
                {showPopover && (
                    <div
                        ref={popoverRef}
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
    );
};

export default ContainerRow;
