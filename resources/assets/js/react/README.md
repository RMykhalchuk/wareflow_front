# React Container Module

Повноцінна React реалізація модуля контейнерів для поступової міграції з jQuery на React.

## Структура проекту

```
resources/assets/js/react/
├── app.jsx                          # Головна точка входу
├── containers/                      # Модуль контейнерів
│   ├── ContainerList.jsx           # Головний компонент списку
│   ├── ContainerTable.jsx          # Таблиця контейнерів
│   ├── ContainerRow.jsx            # Рядок таблиці
│   ├── ContainerFilters.jsx        # Фільтри та пошук
│   ├── ContainerPagination.jsx     # Пагінація
│   ├── localization.js             # Переклади
│   └── api/
│       └── containerApi.js         # API методи
└── components/                      # Спільні компоненти
    ├── LoadingSpinner.jsx          # Індикатор завантаження
    └── ErrorAlert.jsx              # Повідомлення про помилки
```

## Особливості

✅ **Модульна архітектура** - кожен компонент у окремому файлі
✅ **Повторне використання** - спільні компоненти для всього додатку
✅ **API Layer** - окремий шар для взаємодії з бекендом
✅ **Локалізація** - підтримка української та англійської мов
✅ **Поступова міграція** - React та jQuery працюють паралельно
✅ **Webpack** - збірка через Laravel Mix

## Встановлення

### 1. Встановити залежності

```bash
npm install react react-dom @babel/preset-react --save
```

### 2. Зібрати React компоненти

```bash
npm run dev
# або для production
npm run production
```

### 3. Налаштування Laravel Mix

Конфігурація вже додана в `webpack.mix.js`:

```javascript
mix.react('resources/assets/js/react/app.jsx', 'public/js/react/containers-bundle.js')
```

## Використання в Blade

### Базове використання

```blade
@extends('layouts.admin')

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

### Розмонтування компонента

```javascript
// Розмонтувати конкретний компонент
window.ContainerApp.unmount('container-app');

// Розмонтувати всі компоненти
window.ContainerApp.unmountAll();
```

## API Endpoints

Необхідно створити наступні ендпоінти в Laravel:

### GET /containers/api/list

Отримання списку контейнерів з фільтрацією та пагінацією

**Параметри запиту:**
- `page` (int) - номер сторінки
- `per_page` (int) - кількість на сторінці
- `search` (string) - пошуковий запит
- `type` (string) - фільтр за типом
- `reversible` (0|1) - фільтр за реверсивністю

**Відповідь:**
```json
{
    "data": [
        {
            "id": 1,
            "local_id": "001",
            "name": "Контейнер 1",
            "code_format": "CONT-001",
            "type": "Тип 1",
            "reversible": 1
        }
    ],
    "total": 100
}
```

### Приклад контролера

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class ContainerApiController extends Controller
{
    public function list(Request $request)
    {
        $query = Container::query();

        // Пошук
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code_format', 'like', "%{$search}%");
            });
        }

        // Фільтр за типом
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Фільтр за реверсивністю
        if ($request->filled('reversible')) {
            $query->where('reversible', $request->reversible);
        }

        $perPage = $request->input('per_page', 10);
        $containers = $query->paginate($perPage);

        return response()->json([
            'data' => $containers->items(),
            'total' => $containers->total()
        ]);
    }

    public function show($id)
    {
        $container = Container::findOrFail($id);
        return response()->json($container);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code_format' => 'required|string|max:255',
            'type' => 'required|string',
            'reversible' => 'boolean',
        ]);

        $container = Container::create($validated);
        return response()->json($container, 201);
    }

    public function update(Request $request, $id)
    {
        $container = Container::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'code_format' => 'string|max:255',
            'type' => 'string',
            'reversible' => 'boolean',
        ]);

        $container->update($validated);
        return response()->json($container);
    }

    public function destroy($id)
    {
        $container = Container::findOrFail($id);
        $container->delete();
        return response()->json(null, 204);
    }
}
```

### Routes

```php
Route::prefix('containers/api')->group(function () {
    Route::get('/list', [ContainerApiController::class, 'list']);
    Route::get('/{id}', [ContainerApiController::class, 'show']);
    Route::post('/', [ContainerApiController::class, 'store']);
    Route::put('/{id}', [ContainerApiController::class, 'update']);
    Route::delete('/{id}', [ContainerApiController::class, 'destroy']);
});
```

## Додавання нових компонентів

### 1. Створити компонент

```jsx
// resources/assets/js/react/containers/NewComponent.jsx
import React from 'react';

const NewComponent = ({ prop1, prop2 }) => {
    return (
        <div>
            {/* JSX код */}
        </div>
    );
};

export default NewComponent;
```

### 2. Імпортувати в батьківський компонент

```jsx
import NewComponent from './NewComponent';
```

### 3. Перезібрати

```bash
npm run dev
```

## Поступова міграція

Цей підхід дозволяє:

1. **Залишити старий код** - jQuery компоненти продовжують працювати
2. **Поступово мігрувати** - модуль за модулем
3. **Використовувати React** - для нових фіч та рефакторингу старих
4. **Спільні стилі** - Bootstrap CSS працює для обох

### План міграції

- [x] Контейнери - основний список
- [ ] Контейнери - створення/редагування
- [ ] SKU
- [ ] Документи
- [ ] Інвентаризація
- [ ] Локації
- [ ] Транспорт

## Відладка

### Development режим

```bash
npm run watch
```

Source maps включені в dev режимі для зручного дебагу.

### Production режим

```bash
npm run production
```

Мінімізація та оптимізація для продакшну.

## Переваги цього підходу

1. **Модульність** - легко підтримувати та розширювати
2. **Тестування** - кожен компонент можна тестувати окремо
3. **Повторне використання** - компоненти можна використовувати в різних місцях
4. **TypeScript ready** - легко додати типізацію в майбутньому
5. **Сучасний стек** - React 18 з хуками
6. **Поступова міграція** - не потрібно переписувати все відразу

## Наступні кроки

1. Додати TypeScript для типобезпеки
2. Додати React Router для SPA навігації
3. Додати State Management (Redux/Zustand)
4. Додати Unit тести (Jest + React Testing Library)
5. Додати E2E тести (Cypress/Playwright)
6. Мігрувати інші модулі на React
