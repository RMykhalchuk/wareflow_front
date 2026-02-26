<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

final class StaterKitController extends Controller
{
    // without menu
    public function without_menu(): \Illuminate\Contracts\View\View
    {
        $pageConfigs = ['showMenu' => false];

        return view('/content/layout-without-menu', ['pageConfigs' => $pageConfigs]);
    }

}
