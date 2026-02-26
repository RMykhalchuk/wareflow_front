<?php

namespace App\Models\Entities;

use App\Models\Dictionaries\GoodsCategory;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\System\Workspace;
use App\Traits\FilterByWorkspaceTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Categories.
 */
class Categories extends Model
{
    use HasUuids;
    use FilterByWorkspaceTrait;

    /**
     * @var string
     */
    protected $table = 'categories';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var string
     */
    protected $keyType = 'string';
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'active',
        'parent_id',
        'goods_category_id',
        'workspace_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'active' => 'bool',
    ];

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class, 'categories_id');
    }

    /**
     * @return BelongsTo
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    /**
     * @return BelongsTo
     */
    public function goodsCategory(): BelongsTo
    {
        return $this->belongsTo(GoodsCategory::class, 'goods_category_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query): mixed
    {
        return $query->where('active', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeRoot($query): mixed
    {
        return $query->whereNull('parent_id');
    }

    /**
     * @param $query
     * @param int|string $workspaceId
     * @return mixed
     */
    public function scopeForWorkspace($query, int|string $workspaceId)
    {
        return $query->where('workspace_id', $workspaceId);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    /**
     * @return bool
     */
    public function isChild(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * @return bool
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * @return bool
     */
    public function isCategoryEntity(): bool
    {
        return (bool)$this->table === 'categories';
    }

    /**
     * @return $this
     */
    public function rootAncestor(): self
    {
        $node = $this;

        while ($node->parent_id) {
            if ($node->relationLoaded('parent')) {
                $next = $node->getRelation('parent');
            } else {
                $next = static::select('id', 'parent_id', 'goods_category_id', 'workspace_id')
                    ->find($node->parent_id);
            }

            if (!$next) {
                break;
            }
            $node = $next;
        }

        return $node;
    }

    /**
     * @return GoodsCategory|null
     */
    public function goodsCategoryFromRoot(): ?GoodsCategory
    {
        $root = $this->isRoot() ? $this : $this->rootAncestor();

        $root->loadMissing(['goodsCategory:id,name']);

        return $root->goodsCategory;
    }

    /**
     * @return string|null
     */
    public function getGoodsCategoryNameAttribute(): ?string
    {
        return $this->goodsCategoryFromRoot()?->name;
    }

    /**
     * @return bool
     */
    public function isLeaf(): bool
    {
        if (array_key_exists('children_count', $this->attributes)) {
            return ((int)$this->attributes['children_count']) === 0;
        }

        if ($this->relationLoaded('children')) {
            return $this->children->isEmpty();
        }

        return !$this->children()->exists();
    }

    /**
     * @param int $depth
     * @return array
     */
    public static function eagerChildrenWithCounts(int $depth = 4): array
    {
        $with = [];
        $path = 'children';

        for ($i = 0; $i < $depth; $i++) {
            $with[$path] = function ($q) {
                $q->active()->ordered()->withCount('children');
            };
            $with[$path . '.parent'] = function ($q) {
                $q->select('id', 'parent_id');
            };

            $path .= '.children';
        }

        return $with;
    }

    public function hasGoodsInChildren(): bool
    {
        foreach ($this->children as $child) {
            if ($child->has_goods || $child->hasGoodsInChildren()) {
                return true;
            }
        }
        return false;
    }

}
