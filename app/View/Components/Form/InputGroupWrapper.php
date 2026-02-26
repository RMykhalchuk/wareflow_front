<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class InputGroupWrapper extends Component
{
    public $wrapperClass;

    public function __construct($wrapperClass = 'col-12 col-md-6 mb-1')
    {
        $this->wrapperClass = $wrapperClass;
    }

    public function render()
    {
        return view('components.form.input-group-wrapper');
    }
}
