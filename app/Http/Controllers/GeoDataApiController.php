<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class GeoDataApiController extends Controller
{
    public function index()
    {
        $asset_path = '../' . Config::get('app.asset_url') . '/GeoLite2-City-Blocks-IPv4.csv';

        // Read a CSV file
        $handle = fopen($asset_path, "r");
        fgetcsv($handle);
        $arr = [];
        $stored_card_number = [];

        // Iterate over every line of the file
        while (($raw_string = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $geo_arr = [];
            $latitude = $raw_string[0];
            $longitude = $raw_string[1];
            $geo_arr['latitude'] = $latitude;
            $geo_arr['longitude'] = $longitude;

            $getCount = $this->getCoordinateCount($stored_card_number, $geo_arr);
            $stored_card_number[] = $geo_arr;
            $geo_arr['count'] = $getCount;
            $arr[] = $geo_arr;

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
}
