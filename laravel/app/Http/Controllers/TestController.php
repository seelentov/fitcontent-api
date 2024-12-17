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
            $res->getStatusCode();

            if ($res->getStatusCode() >= 400) {
                $errorContent = $res->getBody()->getContents();
                \Log::error("Request failed with status code: " . $res->getStatusCode() . " and content: " . $errorContent);

                throw new \Exception("Request failed with status code: " . $res->getStatusCode() . " and content: " . $errorContent);
            }


            $xmlString = $res->getBody()->getContents();
            $posRes = $this->client->xmlToJson($xmlString);
            return $posRes;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $errorContent = $response->getBody()->getContents();
            \Log::error("Client error: " . $e->getMessage() . "  Response: " . $errorContent);
            return ['error' => $errorContent, 'status_code' => $response->getStatusCode()];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $response = $e->getResponse();
            $errorContent = $response->getBody()->getContents();
            \Log::error("Server error: " . $e->getMessage() . " Response: " . $errorContent);
            return ['error' => $errorContent, 'status_code' => $response->getStatusCode()];
        } catch (\Exception $ex) {
            \Log::error("General error: " . $ex->getMessage());
            return ['error' => $ex->getMessage()];
        }
    }

}
