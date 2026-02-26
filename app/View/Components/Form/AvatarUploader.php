<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class AvatarUploader extends Component
{
    public $id;
    public $name;
    public $imageSrc;
    public $disabled;

    public function __construct($id, $name, $imageSrc = null, $disabled = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->imageSrc = $imageSrc ?? asset('assets/images/avatar_empty.png');
        $this->disabled = $disabled;
    }

    public function render()
    {
        return view('components.form.avatar-uploader');
    }
}

