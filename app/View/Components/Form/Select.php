<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $class;

    public function __construct($id, $name, $label, $placeholder, $class = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.form.select');
    }
}
