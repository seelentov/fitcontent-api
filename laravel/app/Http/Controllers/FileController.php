<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\FileStoreRequest;
use App\Http\Requests\File\FileUpdateRequest;
use App\Services\FileService\FileService;
use App\Services\FileService\IFileService;

class FileController extends Controller
{
    public function __construct(private readonly IFileService $files)
    {
    }

    public function show($id)
    {
        $data = $this->files->getFile($id);
        return $data;
    }
}
