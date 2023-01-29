<?php
/**
 * Created by PhpStorm.
 * User: szyman
 * Date: 05.12.18
 * Time: 18:27
 */

namespace App\Services\Google;


use App\Exceptions\GoogleApiException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Logging\Log;

abstract class GoogleService
{
    protected $apiKey;

    protected $httpClient;

    protected $format = 'json';

    /**
     * GoogleService constructor.
     */
    public function __construct()
    {
        $this->apiKey = env('GOOGLE_API_KEY');

        $this->httpClient = new \GuzzleHttp\Client();
    }

    /**
     * @param $baseUrl
     * @param $params
     * @return mixed
     * @throws GoogleApiException
     */
    public function get($baseUrl, $params)
    {
        $defaultParams = [
            'key' => $this->apiKey
        ];

        $url = $baseUrl . '?' . http_build_query($defaultParams + $params);

        try {
            $response = $this->httpClient->request('GET', $url);
        } catch (GuzzleException $exception) {
            Log::error($exception->getMessage());
            throw new GoogleApiException();
        }

        $data = $this->decodeResponse($response);

        if ($data->status !== 'OK')
            throw new GoogleApiException(isset($data->error_message) ? $data->error_message : "Google API Status: {$data->status}");

        return $data;
    }

    /**
     * @param $response
     * @return mixed
     */
    protected function decodeResponse($response)
    {
        return $this->format == 'json' ? json_decode($response->getBody()) : $response->getBody();
    }
}