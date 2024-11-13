<?php

namespace App\Services\FileService;

use App\Models\File;
use App\Services\Service;
use App\Services\UserService\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;

class FileService extends Service implements IFileService
{
    public function __construct(
        private readonly File $files,
        private readonly UserService $userService
    ) {}
    public function getRootByUser(int $userId): Collection
    {
        $files = $this->files->where([
            ["user_id", $userId],
            ["folder_id", null]
        ])->get();
        return $files;
    }
    public function getById(int $folderId): File|null
    {
        $file = $this->files->find($folderId);
        return $file;
    }

    public function create(array $data)
    {
        $file = $data['file'];
        $user_id = $data['user_id'];

        $user = $this->userService->getById($user_id);

        $userFolder = $user->uuid;

        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $uniqueFilename = uniqid() . '_' . $filename . '.' . $file->getClientOriginalExtension();

        $filePath = $userFolder . '/' . $uniqueFilename;

        $file->storeAs($userFolder, $uniqueFilename, 'public');

        $fileSize = $file->getSize();
        $fileType = File::TYPE_UNKNOWN; // Default to unknown
        $extension = $file->getClientOriginalExtension();

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $fileType = File::TYPE_IMAGE;
                break;
            case 'txt':
            case 'csv':
                $fileType = File::TYPE_TEXT;
                break;
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'ppt':
            case 'pptx':
            case 'pdf':
                $fileType = File::TYPE_DOC;
                break;
            case 'mp3':
            case 'wav':
            case 'ogg':
                $fileType = File::TYPE_AUDIO;
                break;
            case 'mp4':
            case 'avi':
            case 'mov':
                $fileType = File::TYPE_VIDEO;
                break;
            case 'zip':
            case 'rar':
            case '7z':
                $fileType = File::TYPE_ARCHIVE;
                break;
        }

        unset($data["file"]);

        $data["name"] = $filename;
        $data["path"] = $filePath;
        $data["size"] = $fileSize;
        $data["type"] = $fileType; // Add file type to data

        $file = $this->files->create($data);
        return $file;
    }


    public function update(int $id, array $data)
    {
        $file = $this->files->where("id", $id)->update($data);
        return $file;
    }

    public function delete(int $id): void
    {
        $file = $this->files->find($id);

        $filePath = $file->path;

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        } else {
        }

        $this->files->destroy($id);
    }
}
