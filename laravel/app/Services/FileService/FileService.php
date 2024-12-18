<?php

namespace App\Services\FileService;

use App\Models\Traits\Enums\FileType;
use App\Services\Service;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;
class FileService extends Service implements IFileService
{
    use FileType;

    private $redisKey = "files";
    private $redisTTL = 600;

    public function __construct()
    {
    }

    public function getRoot()
    {
        $objects = $this->getObjects();

        $res = [
            'files' => [],
            'folders' => [],
        ];

        foreach ($objects as $obj) {
            $isFile = array_key_exists('folder_id', array: $obj);

            $parentKey = $isFile ? "folder_id" : 'parent_id';

            if ($obj[$parentKey] === null) {
                if ($isFile) {
                    $res['files'][] = $obj;
                } else {
                    $res['folders'][] = $obj;
                }
            }
        }

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
            $isFile = array_key_exists('folder_id', array: $el);

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
        if (Redis::exists($this->redisKey)) {
            return json_decode(Redis::get($this->redisKey), true);
        }

        $data = $this->getObjectsCore();

        Redis::set($this->redisKey, json_encode($data));
        Redis::expire($this->redisKey, $this->redisTTL);

        return $data;
    }

    private function getObjectsCore()
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

        $objList = [];
        $paginator = $s3->getPaginator('ListObjectsV2', [
            'Bucket' => $bucket_name,
        ]);

        foreach ($paginator as $page) {
            foreach ($page['Contents'] as $object) {
                $objList[] = $this->formatObject($object);
            }
        }

        foreach ($objList as &$obj) {
            $isFolder = array_key_exists('parent_id', $obj);
            if ($isFolder) {
                foreach ($objList as $subObj) {
                    $parentId = array_key_exists('parent_id', $subObj)
                        ? $subObj['parent_id']
                        : $subObj['folder_id'];

                    if (
                        $parentId !== null
                        && Crypt::decryptString($parentId) === Crypt::decryptString($obj['id'])
                        && array_key_exists('type', $subObj)
                        && $subObj['type'] === self::TYPE_IMAGE
                    ) {
                        $obj['icon_url'] = $subObj['path'];
                        break;
                    }
                }

                $iconUrl = array_key_exists('icon_url', $obj) ? $obj['icon_url'] : null;

                foreach ($objList as &$subObj2) {
                    $parentId = array_key_exists('parent_id', $subObj2)
                        ? $subObj2['parent_id']
                        : $subObj2['folder_id'];

                    if (
                        $parentId !== null
                        && Crypt::decryptString($parentId) === Crypt::decryptString($obj['id'])
                    ) {
                        $subObj2['icon_url'] = $iconUrl;
                    }
                }

                unset($subObj2);

            }
        }

        unset($obj);

        $objList = array_filter($objList, function ($obj) {
            $isFile = array_key_exists('type', $obj);

            if (!$isFile) {
                return true;
            }

            return $obj['type'] !== self::TYPE_IMAGE;
        });

        return $objList;
    }

    private function formatObject($object)
    {
        $res = [];
        $res['size'] = $object['Size'];
        $res['created_at'] = $object['LastModified'];

        $res['id'] = Crypt::encryptString($object['Key']);

        $res['path'] = str_replace(" ", "%20", $object['Key']);

        $parts = explode('/', $object['Key']);

        $isFolder = str_ends_with($object['Key'], '/');

        $partsCounter = count($parts) - ($isFolder ? 2 : 1);

        $res['name'] = $parts[$partsCounter];

        $res["parent_id"] = null;

        if (count($parts) > 2) {
            $partsJoin = join('/', array_slice($parts, 0, $partsCounter));

            if ($partsJoin !== env("ROOT_FOLDER")) {
                $parentId = Crypt::encryptString($partsJoin . "/");
                $res["parent_id"] = $parentId;
            }
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
