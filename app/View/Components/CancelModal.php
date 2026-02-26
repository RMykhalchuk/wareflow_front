<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CancelModal extends Component
{
    public string $id;
    public string $route;
    public string $title;
    public string $content;
    public string $cancelText;
    public string $confirmText;

    public function __construct(
        string $id = 'cancel_edit_user',
        string $route = '/users',
        string $title = '',
        string $content = '',
        string $cancelText = 'Cancel',
        string $confirmText = 'Confirm'
    ) {
        $this->id = $id;
        $this->route = $route;
        $this->title = $title;
        $this->content = $content;
        $this->cancelText = $cancelText;
        $this->confirmText = $confirmText;
    }

    public function render()
    {
        return view('components.cancel-modal');
    }
}
