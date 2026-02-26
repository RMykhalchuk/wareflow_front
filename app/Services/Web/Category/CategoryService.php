<?php

namespace App\Services\Web\Category;

use App\Models\Entities\Categories;
use App\Services\Api\Category\CategoryServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Category Service.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * @param string $id
     * @return Categories|null
     */
    public function findById(string $id): ?Categories
    {
        return Categories::find($id);
    }

    /**
     * @throws \Throwable
     */
    public function create(array $data): Categories
    {
        return DB::transaction(function () use ($data) {
            /** @var Categories $category */
            $category = Categories::create([
                'name'              => $data['name'],
                'active'            => true,
                'parent_id'         => $data['parent_id'] ?? null,
                'goods_category_id' => $data['goods_category_id'] ?? null,
                'workspace_id'      => Auth::user()?->current_workspace?->id,
            ]);

            return $category->fresh();
        });
    }

    /**
     * @param Categories $category
     * @param array $data
     * @return Categories
     * @throws \Throwable
     */
    public function update(Categories $category, array $data): Categories
    {
        return DB::transaction(function () use ($category, $data) {
            if (array_key_exists('parent_id', $data)) {
                $newParentId = $data['parent_id'];

                if ($newParentId === $category->id) {
                    throw new InvalidArgumentException('Category cannot be its own parent.');
                }

                if ($newParentId) {
                    $parent = Categories::findOrFail($newParentId);

                    if ((int)$parent->workspace_id !== (int)($data['workspace_id'] ?? $category->workspace_id)) {
                        throw new InvalidArgumentException('Parent category must be in the same workspace.');
                    }
                }
            }

            $category->fill([
                'name'              => $data['name']              ?? $category->name,
                'active'            => $data['active']            ?? $category->active,
                'parent_id'         => $data['parent_id']         ?? $category->parent_id,
                'goods_category_id' => $data['goods_category_id'] ?? $category->goods_category_id,
                'workspace_id'      => $data['workspace_id']      ?? $category->workspace_id,
            ])->save();

            return $category->fresh();
        });
    }

    /**
     * @param Categories $category
     * @return void
     * @throws \Throwable
     */
    public function delete(Categories $category): void
    {
        DB::transaction(function () use ($category) {
            $levels  = [[$category->id]];
            $cursor  = [$category->id];
            $visited = [$category->id => true];

            while (!empty($cursor)) {
                $children = Categories::query()
                    ->whereIn('parent_id', $cursor)
                    ->where('workspace_id', $category->workspace_id)
                    ->pluck('id')
                    ->all();

                $children = array_values(array_filter($children, function ($id) use (&$visited) {
                    if (isset($visited[$id])) return false;
                    $visited[$id] = true;
                    return true;
                }));

                if (empty($children)) break;

                $levels[] = $children;
                $cursor   = $children;
            }


            for ($i = count($levels) - 1; $i >= 0; $i--) {
                foreach (array_chunk($levels[$i], 1000) as $chunk) {
                    Categories::whereIn('id', $chunk)->delete();
                }
            }
        });
    }

    /**
     * @param Categories $category
     * @return Categories
     */
    public function children(Categories $category): Categories
    {
        return $category->children()->orderBy('name')->get();
    }

    /**
     * @param Categories $category
     * @param bool $active
     * @return Categories
     */
    public function setActive(Categories $category, bool $active): Categories
    {
        $category->active = $active;
        $category->save();

        return $category->fresh();
    }

    /**
     * @param Categories $category
     * @param string|null $parentId
     * @return Categories
     */
    public function move(Categories $category, ?string $parentId): Categories
    {
        if ($parentId === $category->id) {
            throw new InvalidArgumentException('Category cannot be its own parent.');
        }

        if ($parentId) {
            $parent = Categories::findOrFail($parentId);

            if ((int)$parent->workspace_id !== (int)$category->workspace_id) {
                throw new InvalidArgumentException('Parent category must be in the same workspace.');
            }
        }

        $category->parent_id = $parentId;
        $category->save();

        return $category->fresh();
    }


    /**
     * Main porpose of this function is to get category tree without N+1 error.
     *
     * @return Collection
     */
    public function listRootTree(): Collection
    {
        $workspaceId = auth()->user()->current_workspace?->id ?? null;

        if (!$workspaceId) return collect();

        $all = Categories::query()
            ->select('id','name','active','parent_id','workspace_id','goods_category_id')
            ->where('workspace_id', $workspaceId)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $all->load([
            'workspace:id,name',
            'goodsCategory:id,name',
        ]);

        $goodsByCategory = \DB::table('goods')
            ->select('category_id', \DB::raw('COUNT(*) as cnt'))
            ->whereIn('category_id', $all->pluck('id'))
            ->whereNull('deleted_at')
            ->groupBy('category_id')
            ->pluck('cnt', 'category_id');

        $byParent = $all->groupBy(function ($c) {
            return $c->parent_id ?? '__root__';
        });

        $attach = function (Categories $node) use (&$attach, $byParent, $goodsByCategory) {
            $children = $byParent->get($node->id, collect());

            $node->setRelation('children', $children);
            $node->setAttribute('children_count', $children->count());

            $count = (int) $goodsByCategory->get($node->id, 0);
            $node->setAttribute('goods_count', $count);
            $node->setAttribute('has_goods', $count > 0);

            if ($children->isNotEmpty()) {
                foreach ($children as $child) {
                    $attach($child);
                }
            }
        };

        $roots = $byParent->get('__root__', collect());

        foreach ($roots as $root) {
            $attach($root);
        }

        return $roots->values();
    }

    public function getList(int $perPage = 15): LengthAwarePaginator
    {
        return Categories::orderBy('id', 'desc')->paginate($perPage);
    }
}
