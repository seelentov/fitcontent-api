<?php

namespace App\Http\Controllers;

use App\Http\Requests\Folder\FolderStoreRequest;
use App\Http\Requests\Folder\FolderUpdateRequest;
use App\Services\FileService\FileService;
use App\Services\FileService\IFileService;
use App\Services\FolderService\FolderService;
use App\Services\FolderService\IFolderService;

class FolderController extends Controller
{
    public function __construct(
        private readonly IFileService $files
    ) {
    }

    public function index()
    {
        $data = $this->files->getRoot();
        return $data;
    }

    public function show($id)
    {
        $data = $this->files->getFolder($id);
        return $data;
    }
}
