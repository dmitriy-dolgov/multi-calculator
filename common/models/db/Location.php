<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property string $region Регион (область)
 * @property string $region_id Код региона (области)
 * @property string $district Район
 * @property string $district_id Код района
 * @property string $city Город (населённый пункт)
 * @property string $city_id Код города (населённого пункта)
 * @property string $street Улица
 * @property string $street_id Код улицы
 * @property string $building Дом (строение)
 * @property string $building_id Код дома (строения)
 * @property string $appartment Помещение
 * @property string $zip_code Почтовый индекс
 * @property string $arbitrary_address Произвольная строка адреса
 * @property string $deleted_at
 *
 * @property Shop[] $shops
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deleted_at'], 'safe'],
            [['region', 'region_id', 'district', 'district_id', 'city', 'city_id', 'street', 'street_id', 'building', 'building_id', 'appartment', 'zip_code', 'arbitrary_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region' => Yii::t('app', 'Region'),
            'region_id' => Yii::t('app', 'Region ID'),
            'district' => Yii::t('app', 'District'),
            'district_id' => Yii::t('app', 'District ID'),
            'city' => Yii::t('app', 'City'),
            'city_id' => Yii::t('app', 'City ID'),
            'street' => Yii::t('app', 'Street'),
            'street_id' => Yii::t('app', 'Street ID'),
            'building' => Yii::t('app', 'Building'),
            'building_id' => Yii::t('app', 'Building ID'),
            'appartment' => Yii::t('app', 'Appartment'),
            'zip_code' => Yii::t('app', 'Zip Code'),
            'arbitrary_address' => Yii::t('app', 'Arbitrary Address'),
            'deleted_at' => Yii::t('app', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShops()
    {
        return $this->hasMany(Shop::className(), ['location_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocationQuery(get_called_class());
    }
}
