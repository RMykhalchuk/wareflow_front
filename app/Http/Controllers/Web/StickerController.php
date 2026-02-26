<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class StickerController extends Controller
{
    public function show($type)
    {
        // Дозволені типи стікерів
        $allowed = ['container', 'zone', 'sector', 'cell'];

        if (!in_array($type, $allowed)) {
            abort(404); // якщо такого стікера немає
        }

        return view("stickers.$type");
    }
}
