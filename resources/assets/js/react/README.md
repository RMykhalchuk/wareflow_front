# React Container Widget

React компонент для відображення та управління контейнерами в Blade шаблонах.

## Особливості

- Таблиця контейнерів з пагінацією
- Пошук по назві та коду
- Фільтрація по типу контейнера
- Фільтрація по реверсивності
- Багатомовність (українська, англійська)
- Responsive дизайн
- Контекстне меню для кожного контейнера

## Використання

### Варіант 1: Blade файл з вбудованим React (рекомендовано)

Використовуйте готовий Blade файл `resources/views/container/index-react.blade.php`:

```php
// У routes/web.php
Route::get('/containers', function () {
    return view('container.index-react');
})->name('containers.index');
```

### Варіант 2: Інтеграція в існуючий Blade файл

Додайте наступний код в ваш Blade файл:

```blade
@section('before-style')
    <style>
        .gap-50 {
            gap: 0.5rem;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
    <div id="container-widget-root"></div>
@endsection

@section('page-script')
    <script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

    <script type="text/babel">
        // Скопіюйте код компонента з index-react.blade.php

        const root = ReactDOM.createRoot(document.getElementById('container-widget-root'));
        root.render(
            <ContainerWidget
                locale="{{ app()->getLocale() }}"
                apiBaseUrl="{{ url('/') }}"
            />
        );
    </script>
@endsection
```

## API Ендпоінт

Компонент потребує API ендпоінт для отримання даних контейнерів:

```
GET /containers/api/list
```

Параметри запиту:
- `page` - номер сторінки
- `per_page` - кількість елементів на сторінці
- `search` - пошуковий запит
- `type` - фільтр по типу
- `reversible` - фільтр по реверсивності (0 або 1)

Відповідь:
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

## Приклад контролера Laravel

```php
public function apiList(Request $request)
{
    $query = Container::query();

    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('code_format', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->type) {
        $query->whereHas('type', function($q) use ($request) {
            $q->where('name', $request->type);
        });
    }

    if ($request->filled('reversible')) {
        $query->where('reversible', $request->reversible);
    }

    $perPage = $request->per_page ?? 10;
    $containers = $query->paginate($perPage);

    return response()->json([
        'data' => $containers->items(),
        'total' => $containers->total()
    ]);
}
```

## Props компонента

- `locale` - мова інтерфейсу ('uk' або 'en')
- `apiBaseUrl` - базовий URL для API запитів

## Підтримувані мови

- Українська (uk)
- Англійська (en)

Додайте нові переклади в об'єкт `translations` всередині компонента.
