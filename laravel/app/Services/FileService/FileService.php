<?php

namespace App\Services\FileService;

use App\Models\File;
use App\Models\Traits\Enums\FileType;
use App\Services\Service;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Crypt;
class FileService extends Service implements IFileService
{
    use FileType;

    public function __construct(

    ) {
    }
    public function getObjects()
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
        return $fileList;
    }

    private function formatObject($object)
    {
        $res = [];
        $res['size'] = $object['Size'];
        $res['created_at'] = $object['LastModified'];

        $res['id'] = Crypt::encryptString($object['Key']);

        $object['path'] = $object['Key'];

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

        return $object;
    }

    private function formatFolder($object)
    {
        unset($object['size']);

        unset($object['path']);

        return $object;
    }
}
