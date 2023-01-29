<?php
/**
 * Created by PhpStorm.
 * User: Simek
 * Date: 6/8/2018
 * Time: 3:40 PM
 */
$location = $_GET['loc'];
$data = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($location)."&sensor=true");

$longitude = json_decode($data)->results[0]->geometry->location->lng;
$latitude = json_decode($data)->results[0]->geometry->location->lat;

echo $latitude . ',' . $longitude;