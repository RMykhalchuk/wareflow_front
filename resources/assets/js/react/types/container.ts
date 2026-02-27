export interface Container {
  id: number;
  local_id: string;
  name: string;
  code_format: string;
  type: string;
  reversible: 0 | 1;
}

export interface ContainerListResponse {
  data: Container[];
  total: number;
}

export interface ContainerFilters {
  search: string;
  type: string;
  reversible: string;
}

export interface PaginationState {
  currentPage: number;
  totalPages: number;
  perPage: number;
}

export interface ContainerAppProps {
  locale: 'uk' | 'en';
  apiBaseUrl: string;
}

export type ContainerType = 'Тип 1' | 'Тип 2' | 'Тип 3' | 'Тип 4' | 'Тип 5' | 'Тип 6';

export interface ContainerCreateAppProps {
  locale: 'uk' | 'en';
  apiBaseUrl: string;
  redirectUrl?: string;
  cancelUrl?: string;
}

export interface ContainerCreateFormData {
  name: string;
  type_id: string;
  code_format: string;
  reversible: boolean;
  weight: string;
  max_weight: string;
  height: string;
  width: string;
  length: string;
}
