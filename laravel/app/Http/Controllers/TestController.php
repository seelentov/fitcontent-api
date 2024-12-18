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
    }

    public function test2()
    {
        return;
    }
}
