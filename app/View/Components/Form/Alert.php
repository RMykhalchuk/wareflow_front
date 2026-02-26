<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Alert extends Component
{
    public $id;
    public $type;
    public $message;
    public $class;

    public function __construct($id, $type = 'danger', $message = null, $class = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->message = $message;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.form.alert');
    }
}
