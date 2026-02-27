import React from 'react';

interface ErrorAlertProps {
  error: string;
  title?: string;
}

const ErrorAlert: React.FC<ErrorAlertProps> = ({ error, title = 'Error' }) => {
  return (
    <div className="bg-danger-50 border border-danger-200 text-danger-800 rounded-lg p-4">
      <div className="flex items-start">
        <div className="flex-shrink-0">
          <svg
            className="h-5 w-5 text-danger-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth={1.5}
            stroke="currentColor"
          >
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
          </svg>
        </div>
        <div className="ml-3 flex-1">
          <h3 className="text-sm font-medium">{title}</h3>
          <p className="mt-1 text-sm">{error}</p>
        </div>
      </div>
    </div>
  );
};

export default ErrorAlert;
