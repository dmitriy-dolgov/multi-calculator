<?php

namespace common\models;

use omnilight\sypexgeo\SypexGeo;
use yii\base\Component;

/**
 * Для обработки геолокации пользователя.
 *
 * Class Geo
 * @package common\models
 */
class Geo extends Component
{
    const DEFAULT_GEOLOCATION = [
        'city' => [
            'id' => 524901,
            'lat' => 55.75222,
            'lon' => 37.61556,
            'name_ru' => 'Москва',
            'name_en' => 'Moscow',
        ],
        'region' => [
            'id' => 524894,
            'name_ru' => 'Москва',
            'name_en' => 'Moskva',
            'iso' => 'RU-MOW',
        ],
        'country' => [
            'id' => 185,
            'iso' => 'RU',
            'lat' => 60,
            'lon' => 100,
            'name_ru' => 'Россия',
            'name_en' => 'Russia',
        ],
    ];

    public function getUserGeoPosition()
    {
        $sypexGeo = new SypexGeo([
            'database' => '@root/geo/SxGeoCity.dat',
        ]);

        $geoPosition = false;

        $idAddress = \Yii::$app->params['hard-IP'] ?? $_SERVER['REMOTE_ADDR'];
        if (!$cityFullInfo = $sypexGeo->getCityFull($idAddress)) {
            $cityFullInfo = self::DEFAULT_GEOLOCATION;
        }

        if (!$geoPosition = $this->getGeoPositionFromData($cityFullInfo)) {
            $cityFullInfo = self::DEFAULT_GEOLOCATION;
            $geoPosition = $this->getGeoPositionFromData($cityFullInfo);
        }

        return $geoPosition;
    }

    public function getCityList()
    {
        if (!$cityListJson = file_get_contents(\Yii::getAlias('@root/geo/russian-cities-result-with-id-sorted.json'))) {
            throw new \Exception('Required cities geo file not found.');
        }

        if (!$cityListArr = json_decode($cityListJson, true)) {
            throw new \Exception('Cities geo data have wrong format.');
        }

        return $cityListArr;
    }

    protected function getGeoPositionFromData($data)
    {
        $geoPos = [];

        if (!empty($data['city']['lat'])) {
            $geoPos = $data['city'];
        } elseif (!empty($data['country']['lat'])) {
            $geoPos = $data['country'];
        }

        return $geoPos;
    }
}
