<?php

namespace App\Http\Controllers;

use App\Components\HttpClients\YandexCloudClient;
use App\Services\FileService\IFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class TestController extends Controller
{
    public function __construct(
        private readonly IFileService $fileService
    ) {
    }
    public function test()
    {
        // $aws_access_key_id = env('AWS_ACCESS_KEY_ID');
        // $aws_secret_access_key = env('AWS_SECRET_ACCESS_KEY');

        // $bucket_name = 'fitcontent';
        // $mainlink = 'https://storage.yandexcloud.net/fitcontent/';

        // $s3 = new S3Client([
        //     'version' => 'latest',
        //     'region' => 'ru-central1', // You might need to adjust this region
        //     'credentials' => [
        //         'key' => $aws_access_key_id,
        //         'secret' => $aws_secret_access_key,
        //     ],
        //     'endpoint' => 'https://storage.yandexcloud.net',
        // ]);

        // $files = $this->listFiles($s3, $bucket_name);
        // $programs = $this->classifyFiles($files);
        // $jsonData = $this->generateJson($programs, $mainlink);

        // return $jsonData;

        return $this->fileService->getFiles();
    }

    private function listFiles(S3Client $s3, string $bucketName): array
    {
        $fileList = [];
        $paginator = $s3->getPaginator('ListObjectsV2', [
            'Bucket' => $bucketName,
        ]);

        foreach ($paginator as $page) {
            foreach ($page['Contents'] as $object) {

                $fileList[]['size'] = $object['Size'];
                $fileList[]['created_at'] = $object['LastModified'];
            }
        }
        return $fileList;
    }

    private function classifyFiles(array $fileList): array
    {
        $programs = [];
        foreach ($fileList as $filePath) {
            $parts = explode('/', $filePath);
            if (count($parts) > 2) {
                $programName = $parts[1];
                if (!isset($programs[$programName])) {
                    $programs[$programName] = [
                        'pdfUrl' => '',
                        'videoUrl' => '',
                        'musicUrls' => [],
                        'subtitleUrl' => '',
                    ];
                }
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                if ($extension === 'pdf') {
                    $programs[$programName]['pdfUrl'] = $filePath;
                } elseif (in_array($extension, ['mp4', 'avi', 'mov'])) {
                    $programs[$programName]['videoUrl'] = $filePath;
                } elseif ($extension === 'mp3') {
                    $programs[$programName]['musicUrls'][] = $filePath;
                }
            }
        }
        return $programs;
    }

    private function generateJson(array $programs, string $baseUrl): string
    {
        return json_encode([
            'baseUrl' => $baseUrl,
            'programs' => $programs,
        ]);
    }
}
