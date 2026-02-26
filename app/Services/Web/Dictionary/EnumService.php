<?php

namespace App\Services\Web\Dictionary;

use App\Factories\EnumFactory;

class EnumService {
    public function getDictionaryList(string $dictionaryName)
    {
        if (method_exists(EnumFactory::class, $dictionaryName)) {
            return EnumFactory::$dictionaryName(false);
        }

        return null;
    }
}
