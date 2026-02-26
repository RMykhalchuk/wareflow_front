<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Textarea extends Component
{
    public $id;
    public $name;
    public $placeholder;
    public $label;
    public $rows;
    public $class;

    public function __construct($id, $name, $placeholder, $label = null, $rows = 3, $class = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->label = $label;
        $this->rows = $rows;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.form.textarea');
    }
}
