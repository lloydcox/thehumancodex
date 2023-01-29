<?php
/**
 * Created by PhpStorm.
 * User: szyman
 * Date: 05.12.18
 * Time: 18:24
 */

namespace App\Services\Google;


use App\Services\Google\GoogleService;
use Illuminate\Support\Facades\Cache;

class GoogleGeocodeService extends GoogleService
{
    protected $baseUrl = 'https://maps.googleapis.com/maps/api/geocode/';

    public function find($input)
    {
        $cacheKey = "place::geocode::$input";

        if(Cache::has($cacheKey))
            return Cache::get($cacheKey);

        $data = $this->get($this->serviceUrl(), [
            'address' => $input,
            'sensor' => true
        ]);

        $result = $this->parseResults($data);

        Cache::put($cacheKey, $result, 60*24*30);

        return $result;
    }


    private function serviceUrl()
    {
        return $this->baseUrl . $this->format;
    }

    private function parseResults($data)
    {
        $result = $data->results[0];

        return [
            'status' => $data->status,
            'lng'=>$result->geometry->location->lng,
            'lat'=>$result->geometry->location->lat,
            'location'=>$result->formatted_address
        ];
    }
}
