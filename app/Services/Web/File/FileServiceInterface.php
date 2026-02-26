<?php

namespace App\Services\Web\File;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface FileServiceInterface
{
    public function getFile(string $path): Response|BinaryFileResponse;
}
