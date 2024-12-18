<?php

namespace App\Http\Controllers;

use App\Services\FileService\IFileService;

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
