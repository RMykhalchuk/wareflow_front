<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface StoreFileInterface
{
    public function setFile($file, string $pathName, $model, string $fieldName, $modelId = null);

    public function deleteFile(string $pathName, $model, string $fieldName, $modelId = null);
}
