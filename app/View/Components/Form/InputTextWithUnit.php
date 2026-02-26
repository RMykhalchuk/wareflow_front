<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class InputTextWithUnit extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $unit;
    public $class;
    public $isUnitHtml;

    public function __construct(
        $id,
        $name,
        $placeholder,
        $unit,
        $label = null,
        $class = null,
        $isUnitHtml = false
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->unit = $unit;
        $this->class = $class;
        $this->isUnitHtml = filter_var($isUnitHtml, FILTER_VALIDATE_BOOLEAN); // ensure it's boolean
    }

    public function render()
    {
        return view('components.form.input-text-with-unit');
    }
}
