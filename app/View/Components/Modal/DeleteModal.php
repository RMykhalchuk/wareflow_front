<?php

namespace App\View\Components\Modal;

use Illuminate\View\Component;

class DeleteModal extends Component
{
    public string $modalId;
    public string $action;
    public string $title;
    public string $description;
    public string $cancelText;
    public string $confirmText;

    public function __construct(
        string $modalId,
        string $action,
        string $title,
        string $description,
        string $cancelText,
        string $confirmText,
    ) {
        $this->modalId = $modalId;
        $this->action = $action;
        $this->title = $title;
        $this->description = $description;
        $this->cancelText = $cancelText;
        $this->confirmText = $confirmText;
    }

    public function render()
    {
        return view('components.modal.delete-modal');
    }
}
