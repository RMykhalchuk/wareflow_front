<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class DateInput extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $required;
    public $class;

    public function __construct(
        $id,
        $name,
        $label = null,
        $placeholder = null,
        $required = false,
        $class = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.form.date-input');
    }
}
