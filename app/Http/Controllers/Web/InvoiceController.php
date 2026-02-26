<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Tables\Transport\TableFacade;

final class InvoiceController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('invoice.index');
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('invoice.invoicing');
    }

    public function show(): \Illuminate\Contracts\View\View
    {
        return view('invoice.view');
    }

    public function filter()
    {
        return TableFacade::getFilteredData();
    }

    public function obligations_filter()
    {
        return TableFacade::getFilteredData();
    }

}
