<?php

namespace App\Services\Web\File;

use App\Helpers\CompanyWorkspaceHelper;
use App\Models\Entities\System\FileLoad;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileService implements FileServiceInterface
{
    private const COMMON_PATH = ['company', 'user/avatars', 'goods'];

    public function getFile(string $path): Response|BinaryFileResponse
    {
        $searchPath = substr($path, strlen('uploads/'));
        $storagePath = storage_path($path);

        if (!File::exists($storagePath)) {
            return response('Not found', 404);
        }

        $file = FileLoad::whereRaw("path || '/' || new_name = ?", $searchPath)
            ->first();

        CompanyWorkspaceHelper::setGlobalCompany(Auth::id());

        if ($file->creator_company_id == app(AuthContextService::class)->getCompanyId()
            || Str::contains($path, self::COMMON_PATH)) {
            return response()->file($storagePath);
        }

        return response('Unauthorized', 403);
    }
}
