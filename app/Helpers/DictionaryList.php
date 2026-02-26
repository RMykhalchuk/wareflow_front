<?php

namespace App\Helpers;

final class DictionaryList
{
    public static function list(): array
    {
        return [
            'adr' => 'АДР',
            'cell_type' => 'Тип комірки',
            'cell_status' => 'Статус комірки',
            'company_status' => 'Статус компанії',
            'country' => 'Країна',
            'download_zone' => 'Зона завантаження',
            'measurement_unit' => 'Одиниці вимірювання',
            'package_type' => 'Тип пакування',
            'position' => 'Роль користувача',
            'settlement' => 'Місто',
            'street' => 'Вулиця',
            'storage_type' => 'Тип сховища',
            'transport_brand' => 'Бренд транспорту',
            'transport_download' => 'Тип завантаження',
            'transport_kind' => 'Вид транспорту',
            'transport_type' => 'Тип транспорту',
            'company' => 'Компанії',
            'warehouse' => 'Склад',
            'transport' => 'Транспорт',
            'additional_equipment' => 'Додаткове обладнання',
            'user' => 'Користувачі',
            'document_order' => 'Замовлення (документи)',
            'document_goods_invoice' => 'Товарна накладна (документи)',
            'currencies' => 'Валюта',
            'cargo_type' => 'Тип вантажу',
            'delivery_type' => 'Тип доставки',
            'basis_for_ttn' => 'Підстава для ТТН',
        ];
    }
}
