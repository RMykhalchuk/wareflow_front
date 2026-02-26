import React, { useState } from 'react';
import { useForm, Controller } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useIntl } from 'react-intl';
import { Listbox, Transition } from '@headlessui/react';
import { ChevronUpDownIcon, CheckIcon } from '@heroicons/react/24/outline';
import { containerCreateSchema } from './schema';
import { FormField, InputWithUnit } from './FormField';
import { messages } from '../messages';
import { ContainerApi } from '../api/containerApi';
import type { ContainerCreateAppProps } from '../../types/container';
import type { ContainerCreateValues } from './schema';

const ContainerCreateForm: React.FC<ContainerCreateAppProps> = ({
  locale,
  apiBaseUrl,
  redirectUrl,
  cancelUrl,
}) => {
  const { formatMessage } = useIntl();
  const [serverError, setServerError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  const t = (id: string) => formatMessage({ id });

  const schema = containerCreateSchema(t);

  const {
    register,
    handleSubmit,
    control,
    setValue,
    watch,
    formState: { errors },
  } = useForm<ContainerCreateValues>({
    resolver: zodResolver(schema),
    defaultValues: {
      name: '',
      type_id: '',
      code_format: '',
      reversible: true,
      weight: '',
      max_weight: '',
      height: '',
      width: '',
      length: '',
    },
  });

  const api = new ContainerApi(apiBaseUrl, locale);
  const languagePrefix = locale === 'en' ? '' : `/${locale}`;

  const typeOptions = [
    { value: '1', label: formatMessage(messages.type_1), apiValue: 'Тип 1' },
    { value: '2', label: formatMessage(messages.type_2), apiValue: 'Тип 2' },
    { value: '3', label: formatMessage(messages.type_3), apiValue: 'Тип 3' },
    { value: '4', label: formatMessage(messages.type_4), apiValue: 'Тип 4' },
    { value: '5', label: formatMessage(messages.type_5), apiValue: 'Тип 5' },
    { value: '6', label: formatMessage(messages.type_6), apiValue: 'Тип 6' },
  ];

  const selectedTypeId = watch('type_id');
  const selectedType = typeOptions.find((o) => o.value === selectedTypeId) ?? null;

  const onSubmit = async (data: ContainerCreateValues) => {
    try {
      setSubmitting(true);
      setServerError(null);
      await api.createContainer({
        name: data.name,
        code_format: data.code_format,
        type: selectedType?.apiValue ?? '',
        reversible: data.reversible ? 1 : 0,
      });
      const target = redirectUrl ?? `${apiBaseUrl}${languagePrefix}/containers`;
      window.location.href = target;
    } catch (err) {
      setServerError(err instanceof Error ? err.message : formatMessage(messages.createError));
    } finally {
      setSubmitting(false);
    }
  };

  const handleCancel = () => {
    const target = cancelUrl ?? `${apiBaseUrl}${languagePrefix}/containers`;
    window.location.href = target;
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} noValidate>
      {serverError && (
        <div className="alert alert-danger mb-4" role="alert">
          {serverError}
        </div>
      )}

      <div className="card mb-4">
        <div className="card-header">
          <h6 className="card-title mb-0 fw-semibold">
            {formatMessage(messages.createBaseData)}
          </h6>
        </div>
        <div className="card-body">
          <div className="row g-3">
            <FormField
              label={formatMessage(messages.createName)}
              error={errors.name?.message}
              required
            >
              <input
                {...register('name')}
                type="text"
                placeholder={formatMessage(messages.createNamePlaceholder)}
                className={`form-control ${errors.name ? 'is-invalid' : ''}`}
              />
            </FormField>

            <FormField
              label={formatMessage(messages.createType)}
              error={errors.type_id?.message}
              required
            >
              <Controller
                name="type_id"
                control={control}
                render={({ field }) => (
                  <Listbox
                    value={field.value}
                    onChange={(val) => field.onChange(val)}
                  >
                    <div className="relative">
                      <Listbox.Button
                        className={`relative w-full cursor-pointer rounded border ${
                          errors.type_id ? 'border-red-500' : 'border-gray-300'
                        } bg-white py-2 pl-3 pr-10 text-left text-sm focus:outline-none focus:ring-2 focus:ring-blue-500`}
                      >
                        <span className={`block truncate ${!selectedType ? 'text-gray-400' : 'text-gray-900'}`}>
                          {selectedType ? selectedType.label : formatMessage(messages.createTypePlaceholder)}
                        </span>
                        <span className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                          <ChevronUpDownIcon className="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </span>
                      </Listbox.Button>

                      <Transition
                        leave="transition ease-in duration-100"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                      >
                        <Listbox.Options className="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-sm shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                          {typeOptions.map((option) => (
                            <Listbox.Option
                              key={option.value}
                              value={option.value}
                              className={({ active }) =>
                                `relative cursor-pointer select-none py-2 pl-10 pr-4 ${
                                  active ? 'bg-blue-50 text-blue-900' : 'text-gray-900'
                                }`
                              }
                            >
                              {({ selected }) => (
                                <>
                                  <span className={`block truncate ${selected ? 'font-medium' : 'font-normal'}`}>
                                    {option.label}
                                  </span>
                                  {selected && (
                                    <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600">
                                      <CheckIcon className="h-5 w-5" aria-hidden="true" />
                                    </span>
                                  )}
                                </>
                              )}
                            </Listbox.Option>
                          ))}
                        </Listbox.Options>
                      </Transition>
                    </div>
                  </Listbox>
                )}
              />
            </FormField>

            <FormField
              label={formatMessage(messages.createCodeFormat)}
              error={errors.code_format?.message}
              required
            >
              <input
                {...register('code_format')}
                type="text"
                maxLength={5}
                placeholder={formatMessage(messages.createCodeFormatPlaceholder)}
                className={`form-control ${errors.code_format ? 'is-invalid' : ''}`}
              />
            </FormField>

            <div className="col-12 col-md-6 px-0 mb-3 d-flex align-items-center">
              <Controller
                name="reversible"
                control={control}
                render={({ field }) => (
                  <div className="form-check form-switch">
                    <input
                      id="reversible"
                      type="checkbox"
                      role="switch"
                      checked={field.value}
                      onChange={(e) => field.onChange(e.target.checked)}
                      className="form-check-input"
                    />
                    <label htmlFor="reversible" className="form-check-label ms-2">
                      {formatMessage(messages.createReversible)}
                    </label>
                  </div>
                )}
              />
            </div>
          </div>
        </div>
      </div>

      <div className="card mb-4">
        <div className="card-header">
          <h6 className="card-title mb-0 fw-semibold">
            {formatMessage(messages.createParameters)}
          </h6>
        </div>
        <div className="card-body">
          <div className="row g-3">
            <FormField
              label={formatMessage(messages.createWeight)}
              error={errors.weight?.message}
            >
              <InputWithUnit
                {...register('weight')}
                type="text"
                inputMode="decimal"
                placeholder={formatMessage(messages.createWeightPlaceholder)}
                unit={formatMessage(messages.createWeightUnit)}
                error={!!errors.weight}
              />
            </FormField>

            <FormField
              label={formatMessage(messages.createMaxWeight)}
              error={errors.max_weight?.message}
            >
              <InputWithUnit
                {...register('max_weight')}
                type="text"
                inputMode="decimal"
                placeholder={formatMessage(messages.createMaxWeightPlaceholder)}
                unit={formatMessage(messages.createWeightUnit)}
                error={!!errors.max_weight}
              />
            </FormField>

            <FormField
              label={formatMessage(messages.createHeight)}
              error={errors.height?.message}
            >
              <InputWithUnit
                {...register('height')}
                type="text"
                inputMode="decimal"
                placeholder={formatMessage(messages.createHeightPlaceholder)}
                unit={formatMessage(messages.createHeightUnit)}
                error={!!errors.height}
              />
            </FormField>

            <FormField
              label={formatMessage(messages.createWidth)}
              error={errors.width?.message}
            >
              <InputWithUnit
                {...register('width')}
                type="text"
                inputMode="decimal"
                placeholder={formatMessage(messages.createWidthPlaceholder)}
                unit={formatMessage(messages.createWidthUnit)}
                error={!!errors.width}
              />
            </FormField>

            <FormField
              label={formatMessage(messages.createLength)}
              error={errors.length?.message}
            >
              <InputWithUnit
                {...register('length')}
                type="text"
                inputMode="decimal"
                placeholder={formatMessage(messages.createLengthPlaceholder)}
                unit={formatMessage(messages.createLengthUnit)}
                error={!!errors.length}
              />
            </FormField>
          </div>
        </div>
      </div>

      <div className="d-flex justify-content-end gap-2 mb-3">
        <button
          type="button"
          onClick={handleCancel}
          className="btn btn-flat-secondary"
          disabled={submitting}
        >
          {formatMessage(messages.createCancel)}
        </button>
        <button
          type="submit"
          className="btn btn-primary"
          disabled={submitting}
        >
          {submitting ? formatMessage(messages.createSaving) : formatMessage(messages.createSave)}
        </button>
      </div>
    </form>
  );
};

export default ContainerCreateForm;
