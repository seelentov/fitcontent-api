<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\FileStoreRequest;
use App\Http\Requests\File\FileUpdateRequest;
use App\Services\FileService\FileService;

class FileController extends Controller
{
    public function __construct(private readonly FileService $files) {}

    public function show($id)
    {
        $file = $this->files->getById($id);

        if (is_null($file)) {
            return response()->json(["message" => "Not found"], 404);
        }

        return response()->json($file);
    }
}
