<?php

namespace App\Http\Controllers\Web\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Categories\CategoryStoreRequest;
use App\Http\Requests\Web\Categories\CategoryUpdateRequest;
use App\Models\Entities\Categories as Category;
use App\Services\Api\Category\CategoryServiceInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Category Controller.
 */
class CategoryController extends Controller
{
    /**
     * @param CategoryServiceInterface $categories
     */
    public function __construct(private readonly CategoryServiceInterface $categories)
    {}

    /**
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $categories = $this->categories->listRootTree();

        return view('type-categories.index', compact('categories'));
    }

    /**
     * @param CategoryStoreRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $this->categories->create($request->validated());

        return redirect()->route('type-categories.index');
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(CategoryUpdateRequest $request, string $type_category): RedirectResponse
    {
        $category = Category::whereKey($type_category)->firstOrFail();

        $this->categories->update($category, $request->validated());

        return redirect()->route('type-categories.index');
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->categories->delete($category);

        return redirect()->route('type-categories.index');
    }
}
