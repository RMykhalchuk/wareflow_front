<?php

namespace App\Services\Web\Bookmark;

use App\Models\Entities\System\Bookmark;

class BookmarkService implements BookmarkServiceInterface
{
    public function create(array $data): void
    {
        Bookmark::create($data);
    }

    public function findByKey(string $userId, string $key): ?Bookmark
    {
        return Bookmark::where('user_id', $userId)->where('key', $key)->first();
    }

    public function deleteByKey(string $userId, string $key): void
    {
        Bookmark::where('user_id', $userId)->where('key', $key)->delete();
    }
}
