<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface StoreImageInterface
{
    public function setImage($request, Model $model, string $path, string $column);

    public function deleteImage(Model $model, string $path, string $column);
}
