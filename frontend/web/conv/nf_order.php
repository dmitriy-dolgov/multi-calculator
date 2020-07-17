<?php
/**
 * Сортировка по названию города результата файла nf.php
 */

set_time_limit(1200);

die('blocked');

$rsJson = file_get_contents(__DIR__ . '/russian-cities-result-with-id.json');
$rsArr = json_decode($rsJson, true);

uasort($rsArr, function ($a, $b) {
    if ($a['name'] == $b['name']) {
        return 0;
    }
    return ($a['name'] < $b['name']) ? -1 : 1;
});

file_put_contents(__DIR__ . '/russian-cities-result-with-id-sorted.json', json_encode($rsArr));
echo 'FINISH ORDER';