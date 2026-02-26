<?php

namespace App\Factories;

use App\Models\Container\ContainerByDocument;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\DocumentType;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Goods\GoodsByDocument;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Models\Entities\Transport\Transport;
use App\Models\Entities\WarehouseComponents\Warehouse;

use App\Models\User;

final class TableModelFactory
{
    public function user(): User
    {
        return new User();
    }

    public function additionalEquipment(): AdditionalEquipment
    {
        return new AdditionalEquipment();
    }

    public function company(): Company
    {
        return new Company();
    }

    public function transport(): Transport
    {
        return new Transport();
    }

    public function warehouse(): Warehouse
    {
        return new Warehouse();
    }

    public function goods(): Goods
    {
        return new Goods();
    }

    public function document(): Document
    {
        return new Document();
    }

    public function document_type(): DocumentType
    {
        return new DocumentType();
    }

    public function skuByDocument(): GoodsByDocument
    {
        return new GoodsByDocument();
    }

    public function containerByDocument(): ContainerByDocument
    {
        return new ContainerByDocument();
    }
}
