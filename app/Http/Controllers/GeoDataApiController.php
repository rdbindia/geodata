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
        $lineNumber = 1;
        $arr = [];
        $stored_card_number = [];


        // Iterate over every line of the file
        while (($raw_string = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $geo_arr = [];
            $geoname_id = $raw_string[1];
            $latitude = $raw_string[2];
            $longitude = $raw_string[3];
            $geo_arr['geo_latitude'] = $latitude;
            $geo_arr['geo_longitude'] = $longitude;

//            dd($getCount);
            /*  if (in_array($geo_arr, $stored_card_number)) {
                  $geo_arr['count'] = $getCount;
              }*/
            $getCount = $this->getCoordinateCount($stored_card_number, $geo_arr);
            $stored_card_number[] = $geo_arr;
//            dump($getCount);
            $geo_arr['count'] = $getCount;
            $arr[$geoname_id] = $geo_arr;

            // Parse the raw csv string: "1, a, b, c"

            // into an array: ['1', 'a', 'b', 'c']
            // And do what you need to do with every line

            // Increase the current line
            $lineNumber++;
        }

//        echo $lineNumber;

        fclose($handle);
        return json_encode($arr);
    }

    private function getCoordinateCount($raw_string, array $geo_arr)
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
        $count = count($found) + 1;

        return $count;
    }
}
