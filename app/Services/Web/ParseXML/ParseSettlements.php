<?php

namespace App\Services\Web\ParseXML;

use App\Models\Entities\Address\Region;
use App\Models\Entities\Address\Settlement;
use App\Models\Entities\Address\Street;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class ParseSettlements
{
    private $url;

    public function __construct()
    {
        $this->url = storage_path('files/settlements.xml');
    }

    public function parse()
    {
        $start = microtime(true);
        $dataArray = $this->getArrayFromXml();
        [$regionsArray, $cityArray, $streetArray] = $this->getParsedArray($dataArray);
        if (!Settlement::count()) {
            $this->createRegions($regionsArray);
            $this->createSettlements($cityArray, $regionsArray);
            $this->createStreets($streetArray);
        }
        $end = microtime(true);
        Log::info('PARSE TIME:' . $end - $start);
    }

    private function getParsedArray($dataArray)
    {
        $regionsArray = [];
        $cityArray = [];
        $streetArray = [];
        foreach ($dataArray as $item) {
            $oblName = (string)$item->OBL_NAME;
            $cityName = (string)$item->CITY_NAME;
            $streetName = (string)$item->STREET_NAME;
            $regionsArray[$oblName] = $oblName;
            $cityArray[$oblName . $cityName]['city'] = $cityName;
            $cityArray[$oblName . $cityName]['obl'] = $oblName;
            $streetArray[$streetName] = $streetName;
        }
        // В xml Сімферополь і Київ рахуються як області.
        if (array_key_exists('м.Севастополь', $regionsArray)) {
            unset($regionsArray['м.Севастополь']);
        }

        if (array_key_exists('м.Київ', $regionsArray)) {
            unset($regionsArray['м.Київ']);
        }

        unset($cityArray['Автономна Республіка Крим Не визначений Н.П.']);
        unset($cityArray['Автономна Республіка Крим']);
        unset($streetArray['']);

        return [array_values($regionsArray), array_values($cityArray), array_values($streetArray)];
    }

    private function getArrayFromXml()
    {
        $contents = file_get_contents($this->url);
        return (new SimpleXMLElement($contents))->RECORD;
    }

    private function createRegions($regions)
    {
        $insertRegionsArray = [];
        foreach ($regions as $key => $region) {
            $insertRegionsArray[$key]['name'] = $region;
        }
        Region::insert($insertRegionsArray);
    }

    private function createSettlements($settlementArray, $regionsArray)
    {
        $insertSettlementArray = [];
        foreach ($settlementArray as $key => $settlement) {
            $insertSettlementArray[$key] = [
                'region_id' => array_search($settlement['obl'], $regionsArray) + 1,
                'name' => $settlement['city']
            ];
        }
        Settlement::insert($insertSettlementArray);
    }

    private function createStreets($streetArray)
    {
        $chunkSize = 500; // Number of rows to insert per chunk

        // Use the chunk() method to process the data in chunks
        collect($streetArray)->chunk($chunkSize)->each(function ($chunk) {
            $insertStreetArray = [];

            foreach ($chunk as $street) {
                $trimmedStreet = mb_substr($street, 0, 255); // підтримка Юнікоду
                $insertStreetArray[] = ['name' => $trimmedStreet];
            }
            Street::insert($insertStreetArray);
        });
    }
}
