<?php

namespace App\Services\FolderService;

use App\Models\Folder;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class FolderService extends Service implements IFolderService
{
    public function __construct(
        private readonly Folder $folders,
    ) {}

    public function getRoot(): Collection|Folder|null
    {
        $folders = $this->folders->where([
            ["parent_id", null]
        ])->get();

        return $folders;
    }

    public function getById(int $folderId): Folder|null
    {
        $folder = $this->folders->with('folders')->with('files')->find($folderId);
        return $folder;
    }
}
