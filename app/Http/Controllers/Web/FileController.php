<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\File\FileServiceInterface;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileServiceInterface $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getFile(string $path): Response|BinaryFileResponse
    {
        return $this->fileService->getFile($path);
    }
}
