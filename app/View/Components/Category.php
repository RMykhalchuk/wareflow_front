<?php

namespace App\View\Components;

use Illuminate\View\Component;

final class Category extends Component
{
    public $category;
    public $i;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($category, $i)
    {
        $this->category = $category;
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
        return view('components.category');
    }
}
