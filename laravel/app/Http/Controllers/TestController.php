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
        try {
            $res = $this->client->getClient()->request("GET");
            $xmlString = $res->getBody();
            $posRes = $this->client->xmlToJson($xmlString);
            return $posRes;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}
