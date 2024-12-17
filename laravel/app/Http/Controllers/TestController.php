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
        $res = $this->client->getClient()->request("GET");
        $body = $res->getBody();
        return $body;
    }
}
