<?php

namespace App\Components\HttpClients;

use GuzzleHttp\Client;

abstract class HttpClient
{
    protected $options;
    protected $baseUri;

    public function __construct()
    {
        $this->options = [
            "timeout" => 10.0,
            'verify' => base_path() . '/cacert.pem'
        ];
    }

    public function getClient()
    {
        return new Client($this->options);
    }

    public function xmlToJson($xmlString)
    {
        $xmlObject = simplexml_load_string($xmlString);
        $jsonArray = json_decode(json_encode($xmlObject), true);

        return $jsonArray;
    }

}