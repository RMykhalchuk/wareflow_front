<?php

namespace App\Services\Web\Address;

use App\Models\Entities\Address\Settlement;
use App\Models\Entities\Address\Street;

final class AddressService
{
    public function getStreets()
    {
        if (array_key_exists('id', $_GET)) {
            return Street::find($_GET['id']);
        }

        if (array_key_exists('query', $_GET)) {
            return Street::where('name', 'like', '%' .$_GET['query'].'%')->limit(25)->get();
        }

        return Street::orderBy('name', 'asc')->limit(25)->get();
    }

    public function getSettlements()
    {
        $cities = [
            'м.Вінниця',
            'м.Дніпро',
            'м.Донецьк',
            'м.Житомир',
            'м.Запоріжжя',
            'м.Івано-Франківськ',
            'м.Київ',
            'м.Кропивницький',
            'м.Луганськ',
            'м.Луцьк',
            'м.Львів',
            'м.Миколаїв',
            'м.Одеса',
            'м.Полтава',
            'м.Рівне',
            'м.Суми',
            'м.Тернопіль',
            'м.Ужгород',
            'м.Харків',
            'м.Херсон',
            'м.Хмельницький',
            'м.Черкаси',
            'м.Чернівці',
            'м.Чернігів',
        ];

        if (array_key_exists('id', $_GET)) {
            return Settlement::find($_GET['id']);
        }

        if (array_key_exists('query', $_GET)) {
            $settlements = Settlement::with('region')->where('name', 'like', '%' . $_GET['query'].'%')->limit(25)->get();
            foreach ($settlements as $settlement) {
                $settlement->name = $settlement->name.' ('.$settlement->region->name.')';
            }
            return $settlements;
        } else {
            return Settlement::whereIn('name', $cities)->get();
        }
    }

}
