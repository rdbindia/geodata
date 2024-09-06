<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class GeoDataApiController extends Controller
{
    public function __construct(
        private readonly GeoDataApiService $geoDataApiService,
    )
    {
    }

    public function readFileData(array $polygon_array = [])
    {
        $asset_path = '../' . Config::get('app.asset_url') . '/GeoLite2-City-Blocks-IPv4.csv';

        // Read a CSV file
        $handle = fopen($asset_path, "r");
        fgetcsv($handle);
        $arr = [];
        $new_coordinate_array = [];
        $arr['data'] = [];

        // Iterate over every line of the file
        while (($raw_string = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $geo_arr = [];
            $latitude = $raw_string[0];
            $longitude = $raw_string[1];
            $geo_arr['latitude'] = $latitude;
            $geo_arr['longitude'] = $longitude;

            // When bounding box is set
            if ($polygon_array) {
                $contains = $this->geoDataApiService->contains($geo_arr, $polygon_array);
                if ($contains) {
                    $getCount = $this->geoDataApiService->getCoordinateCount($arr, $geo_arr);
                    $geo_arr['count'] = $getCount;
                    $arr['data'][] = $geo_arr;
                }
            } else {
                // When no bounding box is set
                $getCount = $this->geoDataApiService->getCoordinateCount($new_coordinate_array, $geo_arr);
                $geo_arr['count'] = $getCount;
                $new_coordinate_array[] = $geo_arr;
                $arr['data'][] = $geo_arr;
            }
        }

        fclose($handle);
        return json_encode($arr);
    }

    public function polygon(Request $request)
    {
        $polygon = $request['coordinates'];
        return $this->index($polygon);
    }
}
