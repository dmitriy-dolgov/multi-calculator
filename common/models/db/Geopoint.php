<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "geopoint".
 *
 * @property int $id
 * @property string $name
 * @property string|null $region
 * @property string|null $sub_region
 * @property string|null $code_cdek
 * @property string|null $kladr_code
 * @property string|null $uuid
 * @property string|null $fias_uuid
 * @property string|null $country
 * @property string|null $region_code
 * @property string|null $lat_long Координаты цента пункта в десятичных градусах
 * @property float|null $merchant_coverage_radius Радиус доступного расположения продавцов от середины (км)
 * @property string|null $index Почтовый индекс
 * @property string|null $code_boxberry
 * @property string|null $code_dpd
 */
class Geopoint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'geopoint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_coverage_radius'], 'number'],
            [['name', 'region', 'sub_region', 'uuid', 'fias_uuid', 'code_boxberry'], 'string', 'max' => 255],
            [['code_cdek', 'kladr_code', 'region_code', 'index', 'code_dpd'], 'string', 'max' => 40],
            [['country'], 'string', 'max' => 70],
            [['lat_long'], 'string', 'max' => 60],
            [['code_cdek'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'region' => Yii::t('app', 'Region'),
            'sub_region' => Yii::t('app', 'Sub Region'),
            'code_cdek' => Yii::t('app', 'Code Cdek'),
            'kladr_code' => Yii::t('app', 'Kladr Code'),
            'uuid' => Yii::t('app', 'Uuid'),
            'fias_uuid' => Yii::t('app', 'Fias Uuid'),
            'country' => Yii::t('app', 'Country'),
            'region_code' => Yii::t('app', 'Region Code'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'merchant_coverage_radius' => Yii::t('app', 'Merchant Coverage Radius'),
            'index' => Yii::t('app', 'Index'),
            'code_boxberry' => Yii::t('app', 'Code Boxberry'),
            'code_dpd' => Yii::t('app', 'Code Dpd'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return GeopointQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeopointQuery(get_called_class());
    }
}
