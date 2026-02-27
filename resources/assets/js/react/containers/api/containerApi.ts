import type { Container, ContainerListResponse, ContainerFilters } from '../../types/container';

export class ContainerApi {
  private baseUrl: string;
  private locale: string;

  constructor(baseUrl: string, locale: string = 'uk') {
    this.baseUrl = baseUrl;
    this.locale = locale;
  }

  private getLanguagePrefix(): string {
    return this.locale === 'en' ? '' : `/${this.locale}`;
  }

  async fetchContainers(
    page: number,
    perPage: number,
    filters: ContainerFilters
  ): Promise<ContainerListResponse> {
    const params = new URLSearchParams({
      page: page.toString(),
      per_page: perPage.toString(),
      ...(filters.search && { search: filters.search }),
      ...(filters.type && { type: filters.type }),
      ...(filters.reversible && { reversible: filters.reversible }),
    });

    const url = `${this.baseUrl}${this.getLanguagePrefix()}/containers/api/list?${params}`;

    const response = await fetch(url, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch containers: ${response.statusText}`);
    }

    return response.json();
  }

  async getContainer(id: number): Promise<Container> {
    const url = `${this.baseUrl}${this.getLanguagePrefix()}/containers/api/${id}`;

    const response = await fetch(url, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch container: ${response.statusText}`);
    }

    return response.json();
  }

  async createContainer(data: Omit<Container, 'id' | 'local_id'>): Promise<Container> {
    const url = `${this.baseUrl}${this.getLanguagePrefix()}/containers/api`;

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': this.getCsrfToken(),
      },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      throw new Error(`Failed to create container: ${response.statusText}`);
    }

    return response.json();
  }

  async updateContainer(id: number, data: Partial<Container>): Promise<Container> {
    const url = `${this.baseUrl}${this.getLanguagePrefix()}/containers/api/${id}`;

    const response = await fetch(url, {
      method: 'PUT',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': this.getCsrfToken(),
      },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      throw new Error(`Failed to update container: ${response.statusText}`);
    }

    return response.json();
  }

  async deleteContainer(id: number): Promise<void> {
    const url = `${this.baseUrl}${this.getLanguagePrefix()}/containers/api/${id}`;

    const response = await fetch(url, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': this.getCsrfToken(),
      },
    });

    if (!response.ok) {
      throw new Error(`Failed to delete container: ${response.statusText}`);
    }
  }

  private getCsrfToken(): string {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    return token || '';
  }
}
