<?php

namespace Database\Seeders\Document;

use App\Models\Entities\Document\DoctypeField;
use Illuminate\Database\Seeder;

class DoctypeFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DoctypeField::disableCompanySeparation();
        DoctypeField::updateOrCreate(
            [
                'key' => 'text_field',
            ],
            [
                'title'       => 'Текстове поле',
                'description' => 'Введіть текст',
                'type'        => 'text',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'select_field',
            ],
            [
                'title'       => 'Поле вибору',
                'description' => 'Виберіть значення',
                'type'        => 'select',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'label_field',
            ],
            [
                'title'       => 'Поле етикетка',
                'description' => 'Виберіть одне або декілька значень',
                'type'        => 'label',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'date_field',
            ],
            [
                'title'       => 'Поле дати',
                'description' => 'Виберіть дату',
                'type'        => 'date',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'range_field',
            ],
            [
                'title'       => 'Діапазон',
                'description' => 'Задайте діапазон',
                'type'        => 'range',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'dateRange_field',
            ],
            [
                'title'       => 'Період дат',
                'description' => 'Вкажіть період',
                'type'        => 'dateRange',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'dateTime_field',
            ],
            [
                'title'       => 'Дата і час',
                'description' => 'Вкажіть дату і час',
                'type'        => 'dateTime',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'dateTimeRange_field',
            ],
            [
                'title'       => 'Дата і часові рамки',
                'description' => 'Вкажіть період часу',
                'type'        => 'dateTimeRange',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'timeRange_field',
            ],
            [
                'title'       => 'Часові рамки',
                'description' => 'Вкажіть період часу',
                'type'        => 'timeRange',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'switch_field',
            ],
            [
                'title'       => 'Вмикач / вимикач значення',
                'description' => '',
                'type'        => 'switch',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'uploadFile_field',
            ],
            [
                'title'       => 'Обрати файл',
                'description' => 'Виберіть файл',
                'type'        => 'uploadFile',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'comment_field',
            ],
            [
                'title'       => 'Коментар',
                'description' => 'Введіть коментар',
                'type'        => 'comment',
                'system'      => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'empty_text_field',
            ],
            [
                'title'  => 'Простий текст',
                'type'   => 'text',
                'system' => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'empty_paragraph_field',
            ],
            [
                'title'  => 'Параграф',
                'type'   => 'text',
                'system' => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'empty_date_field',
            ],
            [
                'title'  => 'Дата',
                'type'   => 'date',
                'system' => 1
            ]
        );

        DoctypeField::updateOrCreate(
            [
                'key' => 'empty_select_field',
            ],
            [
                'title'  => 'Селект',
                'type'   => 'select',
                'system' => 1
            ]
        );
        DoctypeField::enableCompanySeparationDisable();
    }
}
