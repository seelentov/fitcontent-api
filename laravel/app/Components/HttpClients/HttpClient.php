<?php

namespace App\Components\HttpClients;

use GuzzleHttp\Client;

abstract class HttpClient extends Client
{
    protected $options;
    protected $baseUri;
    protected $reqOptions;

    public function __construct()
    {
        $this->options = [
            "timeout" => 10.0,
            'verify' => base_path() . '/cacert.pem',
        ];

        $this->reqOptions = [
            'headers' => []
        ];
    }

    public function getClient()
    {
        return new Client($this->options);
    }

    public function req($method = "GET", $uri = "", array $options = [])
    {
        $client = $this->getClient();
        $options = array_merge($this->options, $options); //Merge default options with request-specific ones.

        return $client->request($method, $this->baseUri + $uri, options: array_merge($options, $this->reqOptions));
    }
}