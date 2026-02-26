<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Categories\CategoryStoreRequest;
use App\Http\Requests\Web\Categories\CategoryUpdateRequest;
use App\Models\Dictionaries\GoodsCategory;
use App\Models\Entities\Categories as Category;
use App\Services\Api\Category\CategoryServiceInterface;
use App\Services\Web\Category\CategoryService;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected CategoryService $service;

    /**
     * @param CategoryServiceInterface $categories
     */
    public function __construct(private readonly CategoryServiceInterface $categories)
    {
    }

    /**
     * Get Categories
     * @queryParam page int optional Page number. Example: 2
     */
    #[QueryParameter('page', 'int', required: false, example: 2)]
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $categories = $this->categories->getList((int)$perPage);

        return response()->json($categories);
    }

    /**
     * Get category by id
     */
    public function show($id): JsonResponse
    {
        return response()->json($this->categories->findById($id));
    }

    /**
     * Create category
     */
    #[BodyParameter('erp_id', type: 'string', example: '1234567890', description: 'category erp id')]
    #[BodyParameter('goods_category_id', example: 1, description: 'goods group')]
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        return response()->json($this->categories->create($request->validated()));
    }
    /**
     * Update category
     */

    public function update(CategoryUpdateRequest $request, string $id): JsonResponse
    {
        $category = Category::whereKey($id)->first();
        if(!$category) {
            return response()->json(null, 404);
        } else {
            return response()->json($this->categories->update($category, $request->validated()));

        }
    }

    /**
     * Get goods categories
     *
     * Product group for creating category
     */

    #[QueryParameter('page', 'int', required: false, example: 2)]

    public function getGoodsCategories(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 50);
        return response()->json(GoodsCategory::orderBy('id', 'desc')->paginate($perPage));
    }

}
