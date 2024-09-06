<?php

namespace App\Http\Controllers;

class GeoDataApiService implements GeoDataApiInterface
{
    public function getCoordinateCount(array $raw_string, array $geo_arr): int
    {
        $found = [];
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

    public function contains(array $geo_arr, array $polygon_array): int
    {
        $polygon = $polygon_array[0];
        if ($polygon[0] != $polygon[count($polygon) - 1])
            $polygon[] = $polygon[0];
        $j = 0;
        $oddNodes = false;
        $x = $geo_arr['latitude'];
        $y = $geo_arr['longitude'];
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
