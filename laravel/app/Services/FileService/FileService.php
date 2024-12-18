<?php

namespace App\Services\FileService;

use App\Models\Traits\Enums\FileType;
use App\Services\Service;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Crypt;
class FileService extends Service implements IFileService
{
    use FileType;

    public function __construct()
    {
    }

    public function getRoot()
    {
        $objects = $this->getObjects();

        $rootFolder = null;

        foreach ($objects as $el) {
            if ($el['name'] === env("ROOT_FOLDER")) {
                $rootFolder = $el;
                break;
            }
        }

        $rootFolder = $this->addChildrensToFolder($rootFolder, $objects);

        $res = [
            'files' => $rootFolder['files'],
            'folders' => $rootFolder['folders'],
        ];

        return $res;
    }

    public function getFolder($id)
    {
        $objects = $this->getObjects();
        $folder = $this->getObject($id, $objects);
        $folder = $this->addChildrensToFolder($folder, $objects);
        return $folder;
    }

    public function getFile($id)
    {
        $file = $this->getObject($id);
        return $file;
    }
    private function getObject($id, $objects = null)
    {
        $id = Crypt::decryptString($id);

        if ($objects == null) {
            $objects = $this->getObjects();
        }

        foreach ($objects as $el) {
            if (Crypt::decryptString($el['id']) === $id) {
                return $el;
            }
        }

        return null;
    }

    private function addChildrensToFolder($folder, $objects = null)
    {
        if ($objects == null) {
            $objects = $this->getObjects();
        }

        $folderId = Crypt::decryptString($folder['id']);

        $folders = [];
        $files = [];

        foreach ($objects as $el) {
            $isFile = array_key_exists('folder_id', $el);

            $parentKey = $isFile ? "folder_id" : 'parent_id';

            if ($el[$parentKey] === null) {
                continue;
            }

            $decryptedId = Crypt::decryptString($el[$parentKey]);
            if ($decryptedId === $folderId) {
                if ($isFile) {
                    $files[] = $el;
                } else {
                    $folders[] = $el;
                }
            }
        }

        $folder['folders'] = $folders;
        $folder['files'] = $files;

        return $folder;
    }

    private function getObjects()
    {
        $aws_access_key_id = env('AWS_ACCESS_KEY_ID');
        $aws_secret_access_key = env('AWS_SECRET_ACCESS_KEY');

        $bucket_name = 'fitcontent';

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => 'ru-central1',
            'credentials' => [
                'key' => $aws_access_key_id,
                'secret' => $aws_secret_access_key,
            ],
            'endpoint' => 'https://storage.yandexcloud.net',
        ]);

        $fileList = [];
        $paginator = $s3->getPaginator('ListObjectsV2', [
            'Bucket' => $bucket_name,
        ]);

        foreach ($paginator as $page) {
            foreach ($page['Contents'] as $object) {

                $fileList[] = $this->formatObject($object);
            }
        }

        foreach ($fileList as &$file) {
            $file['icon_url'] = null;

            $isFolder = array_key_exists('folder_id', $file);

            $parentKey = $isFolder ? 'folder_id' : 'parent_id';

            $parentId = $file[$parentKey];

            foreach ($fileList as $subFile) {
                if (
                    array_key_exists('type', $subFile)
                    && $subFile['type'] === self::TYPE_IMAGE
                    && $subFile['folder_id'] === $parentId
                ) {
                    $file['icon_url'] === $subFile['path'];
                }
            }
        }

        return $fileList;
    }

    private function formatObject($object)
    {
        $res = [];
        $res['size'] = $object['Size'];
        $res['created_at'] = $object['LastModified'];

        $res['id'] = Crypt::encryptString($object['Key']);

        $res['path'] = '/' . $object['Key'];

        $parts = explode('/', $object['Key']);

        $isFolder = str_ends_with($object['Key'], '/');

        $partsCounter = count($parts) - ($isFolder ? 2 : 1);

        $res['name'] = $parts[$partsCounter];

        if (count($parts) > 2) {
            $res["parent_id"] = Crypt::encryptString(join('/', array_slice($parts, 0, $partsCounter)) . "/");
        } else {
            $res["parent_id"] = null;
        }

        if (str_contains($res['name'], '.')) {
            $res = $this->formatFile($res);
        } else {
            $res = $this->formatFolder($res);
        }

        return $res;
    }

    private function formatFile($object)
    {
        $object['folder_id'] = $object['parent_id'];

        unset($object['parent_id']);

        $nameParts = explode('.', $object['name']);
        $format = strtolower($nameParts[count($nameParts) - 1]);

        if (
            in_array($format, self::TYPES_IMAGE_LIST)
        ) {
            $object['type'] = self::TYPE_IMAGE;
        } else if (
            in_array($format, self::TYPES_DOC_LIST)
        ) {
            $object['type'] = self::TYPE_DOC;
        } else if (
            in_array($format, self::TYPES_AUDIO_LIST)
        ) {
            $object['type'] = self::TYPE_AUDIO;
        } else if (
            in_array($format, self::TYPES_VIDEO_LIST)
        ) {
            $object['type'] = self::TYPE_VIDEO;
        } else {
            $object['type'] = self::TYPE_UNKNOWN;
        }

        $object['name'] = str_replace('.' . $format, '', $object['name']);

        return $object;
    }

    private function formatFolder($object)
    {
        unset($object['size']);

        unset($object['path']);

        return $object;
    }
}
