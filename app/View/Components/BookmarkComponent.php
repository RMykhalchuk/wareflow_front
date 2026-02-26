<?php

namespace App\View\Components;

use App\Models\Entities\System\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

final class BookmarkComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    #[\Override]
    public function render()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())->get(['id','name','key','page_uri']);
        return view('layouts.bookmarks', compact('bookmarks'));
    }
}
