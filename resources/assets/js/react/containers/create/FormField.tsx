import React from 'react';

interface FormFieldProps {
  label: string;
  error?: string;
  required?: boolean;
  children: React.ReactNode;
}

export const FormField: React.FC<FormFieldProps> = ({ label, error, required, children }) => (
  <div className="col-12 col-md-6 px-0 mb-3">
    <label className="form-label text-sm font-medium text-gray-700">
      {label}
      {required && <span className="text-red-500 ml-1">*</span>}
    </label>
    {children}
    {error && (
      <p className="mt-1 text-sm text-red-600">{error}</p>
    )}
  </div>
);

interface InputWithUnitProps extends React.InputHTMLAttributes<HTMLInputElement> {
  unit: string;
  error?: boolean;
}

export const InputWithUnit: React.FC<InputWithUnitProps> = ({ unit, error, className, ...props }) => (
  <div className="input-group">
    <input
      {...props}
      className={`form-control ${error ? 'is-invalid border-red-500' : ''} ${className || ''}`}
    />
    <span className="input-group-text bg-gray-50 text-gray-500 text-sm">{unit}</span>
  </div>
);
