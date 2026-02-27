# React + TypeScript + Tailwind Container Module

Сучасна React реалізація модуля контейнерів з TypeScript, Tailwind CSS та TanStack Table.

## 🚀 Технології

- **React 18** - сучасний UI фреймворк
- **TypeScript** - типізація для надійності коду
- **Tailwind CSS** - утилітарний CSS фреймворк
- **TanStack Table v8** - потужна бібліотека для таблиць
- **Headless UI** - доступні UI компоненти без стилів
- **Laravel Mix** - збірка через Webpack

## 📁 Структура проекту

```
resources/assets/js/react/
├── app.tsx                          # Точка входу (TypeScript)
├── types/
│   └── container.ts                 # TypeScript інтерфейси
├── containers/                      # Модуль контейнерів
│   ├── ContainerList.tsx           # Головний компонент
│   ├── ContainerTable.tsx          # Таблиця з TanStack Table
│   ├── ContainerFilters.tsx        # Фільтри та пошук
│   ├── localization.ts             # Типізовані переклади
│   └── api/
│       └── containerApi.ts         # API клієнт (TypeScript)
└── components/                      # Спільні компоненти
    ├── LoadingSpinner.tsx          # Індикатор завантаження
    ├── ErrorAlert.tsx              # Повідомлення про помилки
    └── Select.tsx                  # Headless UI селект
```

## 🎯 Особливості

✅ **Без jQuery** - повністю на React
✅ **TypeScript** - повна типізація коду
✅ **Tailwind CSS** - сучасний дизайн без Bootstrap
✅ **TanStack Table** - сортування, пагінація, фільтри
✅ **Headless UI** - доступні селекти та меню
✅ **Модульність** - чистий та підтримуваний код
✅ **Type Safety** - помилки виявляються на етапі компіляції

## 📦 Встановлення

### 1. Встановити залежності

```bash
npm install
```

Це встановить:
- React 18 та React DOM
- TypeScript
- TanStack Table
- Headless UI
- Tailwind CSS
- ts-loader для TypeScript

### 2. Зібрати проект

```bash
# Development
npm run dev

# Watch mode (авто-перезбірка)
npm run watch

# Production (мінімізація)
npm run production

# Перевірка типів
npm run type-check
```

## 🎨 Tailwind CSS

### Конфігурація

Файл `tailwind.config.js` налаштований з:
- Кастомними кольорами (primary, success, danger)
- Підтримкою форм (`@tailwindcss/forms`)
- Без preflight для сумісності з існуючими стилями

### Використання

```tsx
// Приклад використання Tailwind класів
<div className="flex items-center gap-4">
  <button className="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
    Кнопка
  </button>
</div>
```

## 📊 TanStack Table

### Можливості

- **Сортування** - клік по заголовку колонки
- **Пагінація** - серверна пагінація
- **Гнучкість** - повний контроль над рендерингом
- **Performance** - віртуалізація для великих даних

### Приклад використання

```tsx
import { useReactTable, getCoreRowModel } from '@tanstack/react-table';

const table = useReactTable({
  data,
  columns,
  getCoreRowModel: getCoreRowModel(),
  manualPagination: true,
});
```

## 🎭 Headless UI

### Компоненти

**Select** - доступний селект з клавіатурною навігацією:
```tsx
<Select
  value={value}
  onChange={setValue}
  options={[
    { value: '1', label: 'Опція 1' },
    { value: '2', label: 'Опція 2' },
  ]}
/>
```

**Menu** - контекстне меню для дій:
```tsx
<Menu>
  <Menu.Button>Дії</Menu.Button>
  <Menu.Items>
    <Menu.Item>Переглянути</Menu.Item>
    <Menu.Item>Редагувати</Menu.Item>
  </Menu.Items>
</Menu>
```

## 🔧 TypeScript

### Типи

```typescript
// Інтерфейс контейнера
interface Container {
  id: number;
  local_id: string;
  name: string;
  code_format: string;
  type: string;
  reversible: 0 | 1;
}

// Props компонента
interface ContainerAppProps {
  locale: 'uk' | 'en';
  apiBaseUrl: string;
}
```

### Перевірка типів

```bash
npm run type-check
```

## 🌐 API Integration

### Клієнт API

```typescript
const api = new ContainerApi(baseUrl, locale);

// Отримати список
const response = await api.fetchContainers(page, perPage, filters);

// Отримати один
const container = await api.getContainer(id);

// Створити
const newContainer = await api.createContainer(data);

// Оновити
const updated = await api.updateContainer(id, data);

// Видалити
await api.deleteContainer(id);
```

## 📝 Використання в Blade

```blade
@extends('layouts.admin')

@section('before-style')
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
@endsection

@section('content')
    <div id="container-app"></div>
@endsection

@section('page-script')
    <script src="{{ asset('js/react/containers-bundle.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.ContainerApp.mount('container-app', {
                locale: '{{ app()->getLocale() }}',
                apiBaseUrl: '{{ url('/') }}'
            });
        });
    </script>
@endsection
```

## 🎨 Кастомізація дизайну

### Tailwind теми

Відредагуйте `tailwind.config.js`:

```javascript
theme: {
  extend: {
    colors: {
      primary: {
        500: '#your-color',
        600: '#your-darker-color',
      },
    },
  },
}
```

### Компоненти

Кожен компонент має props для кастомізації:

```tsx
<LoadingSpinner size="lg" message="Завантаження..." />
<ErrorAlert error="Помилка" title="Упс!" />
```

## 🧪 Тестування

### Майбутні плани

```bash
# Unit тести
npm run test

# E2E тести
npm run test:e2e

# Coverage
npm run test:coverage
```

## 📚 Локалізація

Всі тексти винесені в `localization.ts`:

```typescript
export const translations: Record<'uk' | 'en', Translations> = {
  uk: {
    title: 'Контейнери',
    search: 'Пошук...',
    // ...
  },
  en: {
    title: 'Containers',
    search: 'Search...',
    // ...
  },
};
```

## 🚀 Переваги нового стеку

### Без jQuery
- ❌ Стара бібліотека з обмеженими можливостями
- ✅ Сучасний React з хуками та функціональними компонентами

### TypeScript
- ❌ JavaScript без типів - помилки в runtime
- ✅ TypeScript - помилки виявляються при розробці

### Tailwind CSS
- ❌ Bootstrap - важкий та застарілий
- ✅ Tailwind - легкий, гнучкий, сучасний

### TanStack Table
- ❌ jqxGrid - платний, важкий, застарілий
- ✅ TanStack Table - безкоштовний, легкий, сучасний

### Headless UI
- ❌ jQuery UI - застарілі компоненти
- ✅ Headless UI - доступні, сучасні, гнучкі

## 📈 Міграція інших модулів

План поступової міграції:

- [x] **Контейнери** - повністю на React + TS + Tailwind
- [ ] **SKU** - наступний модуль
- [ ] **Документи**
- [ ] **Інвентаризація**
- [ ] **Локації**
- [ ] **Транспорт**

## 🔍 Відладка

### Development tools

```bash
# Watch mode з source maps
npm run watch
```

### React DevTools
Встановіть розширення React DevTools для Chrome/Firefox

### TypeScript errors
```bash
npm run type-check
```

## 📖 Корисні посилання

- [React Docs](https://react.dev/)
- [TypeScript Handbook](https://www.typescriptlang.org/docs/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [TanStack Table](https://tanstack.com/table/v8)
- [Headless UI](https://headlessui.com/)

## 🤝 Внесок

Для додавання нових функцій:

1. Створити нову гілку
2. Написати TypeScript код з типами
3. Використовувати Tailwind для стилів
4. Перевірити типи: `npm run type-check`
5. Зібрати: `npm run production`
6. Створити Pull Request

## ⚡ Performance

- **Code splitting** - готовий до впровадження
- **Lazy loading** - компоненти завантажуються за потреби
- **Мінімізація** - в production режимі
- **Tree shaking** - непотрібний код видаляється

## 🎓 Навчання

Якщо ви нові в цих технологіях:

1. Почніть з [React Tutorial](https://react.dev/learn)
2. Вивчіть основи [TypeScript](https://www.typescriptlang.org/docs/handbook/typescript-in-5-minutes.html)
3. Освойте [Tailwind CSS](https://tailwindcss.com/docs/utility-first)
4. Ознайомтесь з [TanStack Table](https://tanstack.com/table/v8/docs/guide/introduction)
