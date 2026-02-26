import { z } from 'zod';

export const containerCreateSchema = (t: (id: string) => string) =>
  z.object({
    name: z
      .string()
      .min(1, t('containers.validation.nameRequired'))
      .max(255, t('containers.validation.nameMaxLength')),
    type_id: z.string().min(1, t('containers.validation.typeRequired')),
    code_format: z
      .string()
      .min(1, t('containers.validation.codeFormatRequired'))
      .max(5, t('containers.validation.codeFormatMaxLength')),
    reversible: z.boolean(),
    weight: z
      .string()
      .refine(
        (v) => v === '' || (!isNaN(parseFloat(v)) && parseFloat(v) >= 0),
        t('containers.validation.weightPositive')
      ),
    max_weight: z
      .string()
      .refine(
        (v) => v === '' || (!isNaN(parseFloat(v)) && parseFloat(v) >= 0),
        t('containers.validation.maxWeightPositive')
      ),
    height: z
      .string()
      .refine(
        (v) => v === '' || (!isNaN(parseFloat(v)) && parseFloat(v) >= 0),
        t('containers.validation.heightPositive')
      ),
    width: z
      .string()
      .refine(
        (v) => v === '' || (!isNaN(parseFloat(v)) && parseFloat(v) >= 0),
        t('containers.validation.widthPositive')
      ),
    length: z
      .string()
      .refine(
        (v) => v === '' || (!isNaN(parseFloat(v)) && parseFloat(v) >= 0),
        t('containers.validation.lengthPositive')
      ),
  });

export type ContainerCreateSchema = ReturnType<typeof containerCreateSchema>;
export type ContainerCreateValues = z.infer<ContainerCreateSchema>;
