<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class SwitchField extends Component
{
    public $id;
    public $name;
    public $label;
    public $checked;
    public $class;

    public function __construct($id, $name, $label, $checked = false, $class = null)
{
    $this->id = $id;
    $this->name = $name;
    $this->label = $label;
    $this->checked = $checked;
    $this->class = $class;
}

    public function render()
{
    return view('components.form.switch');
}
}
