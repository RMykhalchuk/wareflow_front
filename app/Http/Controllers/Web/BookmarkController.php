<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\BookmarkRequest;
use App\Services\Web\Bookmark\BookmarkServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class BookmarkController extends Controller
{
    public function __construct(
        private readonly BookmarkServiceInterface $bookmarkService
    ) {}

    public function store(BookmarkRequest $request): JsonResponse
    {
        $bookmarkData = $request->validated();
        $bookmarkData['user_id'] = Auth::id();

        $this->bookmarkService->create($bookmarkData);

        return response()->json(
            [
                'message' => 'Bookmark created successfully'
            ]);
    }

    public function findByKey(string $key): JsonResponse
    {
        $bookmark = $this->bookmarkService->findByKey(Auth::id(), $key);

        return response()->json([$bookmark]);
    }

    public function deleteByKey(Request $request): JsonResponse
    {
        $this->bookmarkService->deleteByKey(Auth::id(), $request->key);

        return response()->json(
            [
                'message' => 'Bookmark deleted successfully'
            ]);
    }
}
