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
        $res = $this->client->getClient()->request("GET", "", ['debug' => true]);
        $res->getStatusCode();

        if ($res->getStatusCode() !== 200) {
            $errorString = $res->getBody()->getContents();
            return $errorString;
        }

        $xmlString = $res->getBody()->getContents();
        $posRes = $this->client->xmlToJson($xmlString);

        return $posRes;
    }

}
