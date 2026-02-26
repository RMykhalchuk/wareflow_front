export interface Translations {
  title: string;
  addContainer: string;
  search: string;
  id: string;
  name: string;
  code: string;
  type: string;
  reversible: string;
  actions: string;
  view: string;
  edit: string;
  delete: string;
  loading: string;
  error: string;
  noData: string;
  noResults: string;
  all: string;
  yes: string;
  no: string;
  filterByType: string;
  filterByReversible: string;
  type_1: string;
  type_2: string;
  type_3: string;
  type_4: string;
  type_5: string;
  type_6: string;
  rowsPerPage: string;
  showing: string;
  of: string;
  results: string;
  previous: string;
  next: string;
}

export const translations: Record<'uk' | 'en', Translations> = {
  uk: {
    title: 'Контейнери',
    addContainer: 'Додати контейнер',
    search: 'Пошук по назві або коду...',
    id: 'ID',
    name: 'Назва',
    code: 'Код',
    type: 'Тип',
    reversible: 'Реверсивність',
    actions: 'Дії',
    view: 'Переглянути',
    edit: 'Редагувати',
    delete: 'Видалити',
    loading: 'Завантаження...',
    error: 'Помилка завантаження даних',
    noData: 'Немає контейнерів',
    noResults: 'Результатів не знайдено',
    all: 'Всі',
    yes: 'Так',
    no: 'Ні',
    filterByType: 'Тип',
    filterByReversible: 'Реверсивність',
    type_1: 'Тип 1',
    type_2: 'Тип 2',
    type_3: 'Тип 3',
    type_4: 'Тип 4',
    type_5: 'Тип 5',
    type_6: 'Тип 6',
    rowsPerPage: 'Рядків на сторінці',
    showing: 'Показано',
    of: 'з',
    results: 'результатів',
    previous: 'Попередня',
    next: 'Наступна',
  },
  en: {
    title: 'Containers',
    addContainer: 'Add Container',
    search: 'Search by name or code...',
    id: 'ID',
    name: 'Name',
    code: 'Code',
    type: 'Type',
    reversible: 'Reversibility',
    actions: 'Actions',
    view: 'View',
    edit: 'Edit',
    delete: 'Delete',
    loading: 'Loading...',
    error: 'Error loading data',
    noData: 'No containers',
    noResults: 'No results found',
    all: 'All',
    yes: 'Yes',
    no: 'No',
    filterByType: 'Type',
    filterByReversible: 'Reversibility',
    type_1: 'Type 1',
    type_2: 'Type 2',
    type_3: 'Type 3',
    type_4: 'Type 4',
    type_5: 'Type 5',
    type_6: 'Type 6',
    rowsPerPage: 'Rows per page',
    showing: 'Showing',
    of: 'of',
    results: 'results',
    previous: 'Previous',
    next: 'Next',
  },
};

export const getTranslations = (locale: 'uk' | 'en'): Translations => {
  return translations[locale] || translations.uk;
};
