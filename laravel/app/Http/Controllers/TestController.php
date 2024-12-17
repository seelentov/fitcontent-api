<?php

namespace App\Http\Controllers;

use App\Components\HttpClients\YandexCloudClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function __construct(
        private readonly YandexCloudClient $client,
    ) {
    }
    public function test()
    {
        $res = $this->client->get("");
        $data = json_decode($res->getBody(), true);
        return $data;
    }
}
