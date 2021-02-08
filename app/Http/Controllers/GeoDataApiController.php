<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class GeoDataApiController extends Controller
{
    public function index(array $polygon_array = [])
    {
        $asset_path = '../' . Config::get('app.asset_url') . '/GeoLite2-City-Blocks-IPv4.csv';

        // Read a CSV file
        $handle = fopen($asset_path, "r");
        fgetcsv($handle);
        $arr = [];
        $polygon_coordinate_array = [];
        $new_coordinate_array = [];
        $getCount = 0;
        $arr['data']= [];

        // Iterate over every line of the file
        while (($raw_string = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $geo_arr = [];
            $latitude = $raw_string[0];
            $longitude = $raw_string[1];
            $geo_arr['latitude'] = $latitude;
            $geo_arr['longitude'] = $longitude;

            // When bounding box is set
            if ($polygon_array) {
                $contains = $this->contains($geo_arr, $polygon_array);
                if ($contains) {
                    $getCount = $this->getCoordinateCount($arr, $geo_arr);
                    $geo_arr['count'] = $getCount;
                    $arr['data'][] = $geo_arr;
                }
            } else {
                // When no bounding box is set
                $getCount = $this->getCoordinateCount($new_coordinate_array, $geo_arr);
                $geo_arr['count'] = $getCount;
                $new_coordinate_array[] = $geo_arr;
                $arr['data'][] = $geo_arr;
            }
        }

        fclose($handle);
        return json_encode($arr);
    }

    private function getCoordinateCount($raw_string, array $geo_arr): int
    {
        $found = array();
        foreach ($raw_string as $aKey => $aVal) {
            $coincidences = 0;
            foreach ($geo_arr as $pKey => $pVal) {
                if (array_key_exists($pKey, $aVal) && $aVal[$pKey] == $pVal) {
                    $coincidences++;
                }
            }
            if ($coincidences == count($geo_arr)) {
                $found[$aKey] = $aVal;
            }
        }
        return count($found) + 1;
    }

    public function polygon(Request $request)
    {
        $polygon = $request['coordinates'];
        return $this->index($polygon);
    }

    function contains($point, $polygon)
    {
        $polygon = $polygon[0];
        if ($polygon[0] != $polygon[count($polygon) - 1])
            $polygon[count($polygon)] = $polygon[0];
        $j = 0;
        $oddNodes = false;
        $x = $point['latitude'];
        $y = $point['longitude'];
        $n = count($polygon);
        for ($i = 0; $i < $n; $i++) {
            $j++;
            if ($j == $n) {
                $j = 0;
            }
            if ((($polygon[$i][0] < $y) && ($polygon[$j][0] >= $y)) || (($polygon[$j][0] < $y) && ($polygon[$i][0] >=
                        $y))) {
                if ($polygon[$i][1] + ($y - $polygon[$i][0]) / ($polygon[$j][0] - $polygon[$i][0]) * ($polygon[$j][1] -
                        $polygon[$i][1]) < $x) {
                    $oddNodes = !$oddNodes;
                }
            }
        }
        return $oddNodes;
    }
}
