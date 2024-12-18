<?php

namespace App\Http\Controllers;

use App\Components\HttpClients\YandexCloudClient;
use App\Services\FileService\IFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Crypt;

class TestController extends Controller
{
    public function __construct(
        private readonly IFileService $fileService
    ) {
    }
    public function test()
    {
        return $this->fileService->getObjects();
    }

    public function test2()
    {
        dump(Crypt::decryptString("eyJpdiI6InBKaFNGRm1ZNlZCMFBYY0phR1p1MlE9PSIsInZhbHVlIjoidng4ZDlma1Rjb1VhdXRFbzlrS2IwQT09IiwibWFjIjoiZDJkZjg1NzYyZjNiOTU0NTA0ZGUxNjBiYjcyN2UyN2ViMDhkMjdlYzBiMzY2ZmJjODhlYzU2ZDVkOWJjOGFiZiIsInRhZyI6IiJ9"));
        dd(Crypt::decryptString("eyJpdiI6InNYZ1RSYlUwZVh6NlgrWjR6d1BMdFE9PSIsInZhbHVlIjoiZ0RTdThXbkU2QnBCNFErcFY5bnA3QT09IiwibWFjIjoiNTYyODg4MGE0MjAxNmFmYmJlYTExNjljMDc0YmU0M2VkZWZkNTdjNjFkOGUwY2JhY2VjZjIzMjdlNWRjOGE2MSIsInRhZyI6IiJ9"));
        return;
    }
}
