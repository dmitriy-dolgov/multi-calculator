<?php

namespace common\models\db;

use Yii;
use Da\User\Model\Profile as BaseProfile;

/**
 * Database fields:
 * @property string|null $company_lat_long Широта долгота предприятия или пользователя через точку с запятой
 * @property string|null $status Статус: активен, тест или др.
 * @property string|null $icon_name
 * @property string|null $icon_color
 * @property string|null $icon_image_path
 * @property string|null $facility_image_path
 * @property string|null $schedule
 */
class Profile extends BaseProfile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['company_lat_longLength'] = ['company_lat_long', 'string', 'max' => 40];
        $rules['company_lat_longTrim'] = ['company_lat_long', 'trim'];
        $rules['company_lat_longAllowedSymbols'] = [
            'company_lat_long',
            'match',
            'not' => true,
            'pattern' => '/[^0-9\.;]/',
            'message' => Yii::t('app', 'The longitude value has the wrong characters.'),
        ];
        $rules['websiteUnique'] = ['website', 'unique'];
        $rules['websiteDefault'] = ['website', 'default', 'value' => null];

        $rules['company_statusLength'] = ['status', 'string', 'max' => 40];
        $rules['company_statusTrim'] = ['status', 'trim'];

        $rules['icon_nameLength'] = ['icon_name', 'string', 'max' => 70];
        $rules['icon_nameTrim'] = ['icon_name', 'trim'];

        $rules['icon_colorLength'] = ['icon_color', 'string', 'max' => 255];
        $rules['icon_colorTrim'] = ['icon_color', 'trim'];

        $rules['icon_image_pathLength'] = ['icon_image_path', 'string', 'max' => 255];
        $rules['icon_image_pathTrim'] = ['icon_image_path', 'trim'];

        $rules['facility_image_pathLength'] = ['facility_image_path', 'string', 'max' => 255];
        $rules['facility_image_pathTrim'] = ['facility_image_path', 'trim'];

        $rules['scheduleString'] = ['schedule', 'string'];

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['company_lat_long'] = \Yii::t('app', 'Longitude, latitude of company');
        $labels['status'] = \Yii::t('app', 'Current status');
        $labels['icon_name'] = \Yii::t('app', 'Icon name');
        $labels['icon_color'] = \Yii::t('app', 'Icon color');
        $labels['icon_image_path'] = \Yii::t('app', 'Icon image path');
        $labels['facility_image_path'] = \Yii::t('app', 'Facility image path');
        $labels['schedule'] = \Yii::t('app', 'Schedule');

        return $labels;
    }

    public function getProfileByUrl($url)
    {
        $profile = null;

        $urlParsed = parse_url($url);

        //TODO: выносить домен отдельно при сохранении в другое поле для ускорения
        $profiles = Profile::find()->select(['user_id', 'website'])->all();
        foreach ($profiles as $pr) {
            if ($pr['website']) {
                $prWebParsed = parse_url($pr['website']);
                if ($urlParsed['host'] == $prWebParsed['host']) {
                    $profile = $pr;
                    break;
                }
            }
        }

        return $profile;
    }

    public function getStatuses()
    {
        return [
            [
                'id' => '',
                'name' => Yii::t('app', 'Not active'),
                'hint' => Yii::t('app', 'Your pizzeria is NOT listed on the pizzerias.'),
            ],
            [
                'id' => 'active-accept-orders',
                'name' => Yii::t('app', 'Active'),
                'hint' => Yii::t('app', 'Your pizzeria is on the list of pizzerias and can accept orders.'),
            ],
        ];
    }
}
