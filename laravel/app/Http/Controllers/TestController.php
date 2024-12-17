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
        $xmlString = $res->getBody();
        $posRes = $this->xmlToJson($xmlString);
        return $posRes;
    }

    private function xmlToJson($xmlString)
    {
        $xmlObject = simplexml_load_string($xmlString);
        $jsonArray = json_decode(json_encode($xmlObject), true);

        return $jsonArray;
    }

}
