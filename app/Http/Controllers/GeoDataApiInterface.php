<?php

namespace App\Http\Controllers;

interface GeoDataApiInterface
{
    public function getCoordinateCount(array $raw_string, array $geo_arr): int;

    public function contains(array $geo_arr, array $polygon_array):int;
}
