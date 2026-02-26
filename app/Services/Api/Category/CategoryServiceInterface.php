<?php

namespace App\Services\Api\Category;

use App\Models\Entities\Categories;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Category Service Interface.
 */
interface CategoryServiceInterface
{
    /**
     * @param string $id
     * @return Categories|null
     */
    public function findById(string $id): ?Categories;

    /**
     * @param array $data
     * @return Categories
     */
    public function create(array $data): Categories;

    /**
     * @param Categories $category
     * @param array $data
     * @return Categories
     */
    public function update(Categories $category, array $data): Categories;

    /**
     * @param Categories $category
     * @return void
     */
    public function delete(Categories $category): void;

    /**
     * @param Categories $category
     * @return Categories
     */
    public function children(Categories $category): Categories;

    /**
     * @param Categories $category
     * @param bool $active
     * @return Categories
     */
    public function setActive(Categories $category, bool $active): Categories;

    /**
     * @param Categories $category
     * @param string|null $parentId
     * @return Categories
     */
    public function move(Categories $category, ?string $parentId): Categories;

    /**
     * @return Collection
     */
    public function listRootTree(): Collection;

    public function getList(int $perPage = 15): LengthAwarePaginator;
}
