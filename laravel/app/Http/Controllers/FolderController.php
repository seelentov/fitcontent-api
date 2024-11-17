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
        private readonly IFolderService $folders,
        private readonly IFileService $files
    ) {}

    public function index()
    {
        $folders = $this->folders->getRoot();
        $files = $this->files->getRoot();

        return response()->json([
            'folders' => $folders,
            'files' => $files,
        ]);
    }

    public function show($id)
    {
        $folder = $this->folders->getById($id);

        if (is_null($folder)) {
            return response()->json(["message" => "Not found"], 404);
        }

        return response()->json($folder);
    }
}
