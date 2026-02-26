<?php

namespace App\Services\Web\Bookmark;

interface BookmarkServiceInterface
{
    public function create(array $data): void;

    public function findByKey(string $userId, string $key): ?\App\Models\Entities\System\Bookmark;

    public function deleteByKey(string $userId, string $key): void;
}
