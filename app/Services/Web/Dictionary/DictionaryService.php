<?php

namespace App\Services\Web\Dictionary;

use App\Factories\DictionaryFactory;
use App\Factories\EntityFactory;
use App\Models\Entities\Goods\Goods;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Translatable\HasTranslations;

final class DictionaryService
{
    public function getDictionaryList(string $dictionaryName)
    {
        $dictionary = $this->resolveDictionary($dictionaryName);

        if ($dictionary === null) {
            return null;
        }

        return $this->applyFilters($dictionary);
    }

    /**
     * Визначає джерело словника (DictionaryFactory або EntityFactory).
     */
    private function resolveDictionary(string $dictionaryName)
    {
        if (method_exists(DictionaryFactory::class, $dictionaryName)) {
            return DictionaryFactory::$dictionaryName(false);
        }

        $entityFactory = new EntityFactory();

        if (method_exists($entityFactory, $dictionaryName)) {
            return $entityFactory->$dictionaryName(false);
        }

        return null;
    }

    /**
     * Застосовує фільтри (id, query) до словника.
     */
    private function applyFilters($dictionary)
    {
        $id = request('id');
        $query = request('query');
        $customSearch = request('custom_search');
        $noLimit = request()->boolean('no_limit');
        if (!is_array($dictionary)) {
            if ($id) {
                $item = $dictionary->find($id);
                return $this->translateItem($item);
            }

            if ($query) {
                if ($customSearch == false) {
                    $dictionary = $this->customWhereNameLike($dictionary, $query);
                }
            }

            if ($noLimit) {
                return $dictionary->get()->map(
                    fn($item) => $this->translateItem($item)
                );
            }
            $locale = app()->getLocale();
            $items = $dictionary->limit(15)->get();
            $translated = $items->map(function ($item) use ($locale) {
                $data = $item->toArray();
                if (method_exists($item, 'getTranslation')) {
                    $data['name'] = $item->getTranslation('name', $locale);
                }
                if (isset($item->measurement_unit) && method_exists($item->measurement_unit, 'getTranslation')) {
                    $data['measurement_unit']['name'] = $item->measurement_unit->getTranslation('name', $locale);
                }
                if (isset($item->manufacturer_country) && method_exists($item->manufacturer_country, 'getTranslation')) {
                    $data['manufacturer_country']['name'] = $item->manufacturer_country->getTranslation('name', $locale);
                }
                if ($item instanceof Goods) {
                    $data['barcode'] = $item->barcodes->first()?->barcode;
                }
                return $data;
            });
            return $translated;
        }

        // Якщо це звичайний масив
        if ($id) {
            return $dictionary[$id - 1] ?? null;
        }

        if ($query) {
            return $this->findElementsBySubstringKey($dictionary, $query);
        }

        return $dictionary;
    }

    /**
     * Пошук у масиві словника по підрядку в полі name.
     */
    private function findElementsBySubstringKey(array $array, string $substring): array
    {
        return array_values(array_filter($array, function ($item) use ($substring) {
            return isset($item['name']) && str_contains($item['name'], $substring);
        }));
    }

    //    Translate field name helper
    private function translateItem($item)
    {
        if (!$item) {
            return null;
        }

        $locale = app()->getLocale();
        $data = $item->toArray();

        if (method_exists($item, 'getTranslation')) {
            $data['name'] = $item->getTranslation('name', $locale);
        }

        return $data;
    }

    private function customWhereNameLike($dictionary, $query)
    {
        $model = $dictionary->getModel();

        if (property_exists($model, 'searchField')) {
            return $dictionary->where($model::$searchField, 'ilike', "%{$query}%");
        }

        if (
            in_array(HasTranslations::class, class_uses_recursive($model)) &&
            property_exists($model, 'translatable') &&
            in_array('name', $model->translatable)
        ) {
            $locale = app()->getLocale();
            return $dictionary->whereRaw(
                "name->>? ILIKE ?",
                [$locale, "%{$query}%"]
            );
        }
        if (Schema::hasColumn($model->getTable(), 'name')) {
            return $dictionary->where('name', 'ilike', "%{$query}%");
        }

        return $dictionary;
    }
}

