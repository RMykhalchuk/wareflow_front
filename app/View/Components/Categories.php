<?php

namespace App\View\Components;

use Illuminate\View\Component;

final class Categories extends Component
{
    public $categories;
    public $i;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($categories, $i)
    {
        $this->categories = $categories;
        $this->i = $i;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    #[\Override]
    public function render()
    {
        return view('components.categories');
    }
}
