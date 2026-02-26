<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

final class ResidueController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('residue-control.index');
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('residue-control.create');
    }

    public function catalog(): \Illuminate\Contracts\View\View
    {
        return view('residue-control.catalogue');
    }

}
