<?php
/**
 * Из файла RU.txt вычленим id городов для файла russian-cities.json (https://github.com/pensnarik/russian-cities/)
 * Т. е. надо получить идентификаторы для sypexgeo
 *
 */

die('blocked');

set_time_limit(1200);

$rsJson = file_get_contents(__DIR__ . '/russian-cities.json');
$rsArr = json_decode($rsJson, true);
$resultArr = [];

if (($handle = fopen(__DIR__ . '/RU.txt', "r")) !== false) {
    while (($data = fgetcsv($handle, 0, "\t")) !== false) {
        $geonameid = $data[0];
        $alternatenames = $data[3];
        $latitude = $data[4];
        $longitude = $data[5];
        $featureClass = $data[6];
        $featureCode = $data[7];
        $population = $data[14];

        if (!$population || !$alternatenames || $featureClass != 'P' || in_array($featureCode, ['PPLH', 'STLMT', 'PPLX'])) {
            continue;
        }

        $alternatenamesArr = explode(',', $alternatenames);

        foreach ($rsArr as $rs) {
            if (in_array($rs['name'], $alternatenamesArr)) {
                if (abs($latitude - $rs['coords']['lat']) > 0.05
                    || abs($longitude - $rs['coords']['lon']) > 0.05) {
                    $rs['warn-coord'] = true;
                }
                if (abs($latitude - $rs['coords']['lat']) > 0.1
                    || abs($longitude - $rs['coords']['lon']) > 0.1) {
                    continue;
                }

                $resultArr[$geonameid] = $rs;
            }
        }
    }
    fclose($handle);
}

file_put_contents('russian-cities-result-with-id.json', json_encode($resultArr));
echo 'FINISH';