<?php

namespace Database\Seeders\Document;

use App\Enums\Documents\DocumentKind;
use App\Models\Entities\Document\DoctypeStructure;
use Illuminate\Database\Seeder;

class DoctypeStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DoctypeStructure::updateOrCreate(
            ['kind' => DocumentKind::ARRIVAL], // шукати тільки по kind
            [
                'settings' => [
                    "document_type" => [],
                    "fields" => [
                        "header" => [
                            "1select_field_1" => [
                                "id" => 1,
                                "name" => "Постачальник",
                                "type" => "select",
                                "system" => true,
                                "required" => true,
                                "hint" => "Виберіть значення",
                                "directory" => "company"
                            ],
                            "2select_field_2" => [
                                "id" => 2,
                                "name" => "Місце приймання",
                                "type" => "select",
                                "system" => true,
                                "required" => true,
                                "hint" => "Виберіть значення",
                                "directory" => "cells_by_warehouse_and_receiving_type"
                            ],
                            "3text_field_3" => [
                                "id" => 3,
                                "name" => "Коментар",
                                "type" => "comment",
                                "system" => true,
                                "required" => true,
                                "hint" => "Введіть коментар"
                            ]
                        ]
                    ]
                ]
            ]
        );

        DoctypeStructure::updateOrCreate(
            ['kind' => DocumentKind::OUTCOME],
            [
                'settings' => [
                    "document_type" => [],
                    "fields" => [
                        "header" => [
                            "1select_field_1" => [
                                "id" => 1,
                                "name" => "Постачальник",
                                "type" => "select",
                                "system" => true,
                                "required" => true,
                                "hint" => "Виберіть значення",
                                "directory" => "company"
                            ],
                            "2select_field_2" => [
                                "id" => 2,
                                "name" => "Місце приймання",
                                "type" => "select",
                                "system" => true,
                                "required" => true,
                                "hint" => "Виберіть значення",
                                "directory" => "cells_by_warehouse_and_receiving_type"
                            ],
                            "3text_field_3" => [
                                "id" => 3,
                                "name" => "Коментар",
                                "type" => "comment",
                                "system" => true,
                                "required" => true,
                                "hint" => "Введіть коментар"
                            ]
                        ]
                    ]
                ]
            ]
        );

    }
}
