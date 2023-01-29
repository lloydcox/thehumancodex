<?php
/**
 * Created by PhpStorm.
 * User: szyman
 * Date: 05.12.18
 * Time: 19:02
 */

namespace App\Http\Controllers\API;


use App\Exceptions\GoogleApiException;
use App\Services\Google\GoogleAutocompleteService;
use Illuminate\Http\Request;

class SearchController
{
    public function places(Request $request, GoogleAutocompleteService $googlePlaces)
    {
        try {
            $response = $googlePlaces->search($request->get('query'));
            return [
                'status' => 'success',
                'data' => $response['places'],
                'message' => ''
            ];
        } catch (GoogleApiException $e) {
            return [
                'status' => 'error',
                'data' => [],
                'message' => 'No suggestions found'
            ];
        }
    }
}