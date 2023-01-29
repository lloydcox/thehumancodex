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

class GoogleAutocompleteService extends GoogleService
{
    protected $baseUrl = 'https://maps.googleapis.com/maps/api/place/';

    /**
     * @param $input
     * @return mixed
     * @throws \App\Exceptions\GoogleApiException
     */
    public function search($input)
    {
        $cacheKey = "place::autocomplete::$input";

        if(Cache::has($cacheKey))
            return Cache::get($cacheKey);

        $data = $this->get($this->serviceUrl(), [
            'input' => $input,
            'types' => '(cities)'
        ]);

        $result = $this->parseResults($data);

        Cache::put($cacheKey, $result, 60*24*30);

        return $result;
    }

    /**
     * @return string
     */
    private function serviceUrl()
    {
        return $this->baseUrl . 'autocomplete/' . $this->format;
    }

    /**
     * @param $data
     * @return array
     */
    private function parseResults($data)
    {
        $places = collect($data->predictions)->pluck('terms')->map(function($terms) {
            return collect([
                'city' => array_first($terms)->value,
                'country' => array_last($terms)->value ?? null
            ]);
        });

        return [
            'status' => $data->status,
            'places' => $places->unique(),
        ];
    }
}