<?php

namespace App\Services\FileService;

use App\Models\File;
use App\Services\Service;
use Aws\S3\S3Client;

class FileService extends Service implements IFileService
{
    public function __construct(
        private readonly File $files,
    ) {
    }
    public function getFiles()
    {
        $aws_access_key_id = env('AWS_ACCESS_KEY_ID');
        $aws_secret_access_key = env('AWS_SECRET_ACCESS_KEY');

        $bucket_name = 'fitcontent';

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => 'ru-central1', // You might need to adjust this region
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
                $fileList[] = $object['Key'];
            }
        }
        return $fileList;
    }


}
