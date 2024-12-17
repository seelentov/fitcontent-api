<?php


namespace App\Components\HttpClients;

class YandexCloudClient extends HttpClient
{
    public function __construct()
    {
        $aws_access_key_id = env("AWS_ACCESS_KEY_ID");
        $aws_secret_access_key = env("AWS_SECRET_ACCESS_KEY");

        parent::__construct();
        $this->options["base_uri"] = "https://storage.yandexcloud.net/fitcontent/";
        $this->options['headers'] = [
            'Authorization' => 'AWS ' . $aws_access_key_id . ':' . $aws_secret_access_key,
        ];
    }
}