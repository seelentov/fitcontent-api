<?php

namespace App\Services\FileService;

use App\Models\File;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class FileService extends Service implements IFileService
{
    public function __construct(
        private readonly File $files,
    ) {}
    public function getRoot(): Collection|File|null
    {
        $folders = $this->files->where([
            ["folder_id", null]
        ])->orderBy("position", "ASC")->get();

        return $folders;
    }
    public function getById(int $folderId): File|null
    {
        $file = $this->files->find($folderId);
        return $file;
    }
}
