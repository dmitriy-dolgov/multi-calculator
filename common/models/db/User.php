<?php

namespace common\models\db;

use common\models\traits\ObjectHandling;
use common\models\UploadProfileIconImageForm;
use Da\User\Model\User as BaseUser;
use Yii;

/**
 * Database fields:
 * @property string $login_lat_long
 * @property string order_uid
 * @property string language
 * Defined relations:
 * @property CoWorker[] $coWorkers
 * @property Component[] $components
 * @property ComponentSwitchGroup[] $componentSwitchGroups
 * @property ShopOrder[] $shopOrders
 * @property ShopOrderSignal $shopOrderSignal
 * @property ShopOrderStatus[] $shopOrderStatuses
 * @property ShopOrderUser[] $shopOrderUsers
 * @property ShopOrder[] $shopOrders0
 */
class User extends BaseUser
{
    use ObjectHandling;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['login_lat_longLength'] = ['login_lat_long', 'string', 'max' => 40];
        $rules['login_lat_longTrim'] = ['login_lat_long', 'trim'];
        $rules['login_lat_longAllowedSymbols'] = [
            'login_lat_long',
            'match',
            'not' => true,
            'pattern' => '/[^0-9\.;]/',
            'message' => Yii::t('app', 'The latitude value has the wrong characters.'),
        ];

        $rules['order_uidLength'] = ['order_uid', 'string', 'max' => 50];
        $rules['order_uidUnique'] = [
            'order_uid',
            'unique',
            'message' => Yii::t('app', 'Such order UID already exists.'),
        ];

        $rules['languageLength'] = ['language', 'string', 'max' => 20];

        return $rules;
    }

    public function beforeSave($insert)
    {
        if (!$this->order_uid) {
            try {
                for (; ;) {
                    $bytes = random_bytes(10);
                    $order_uid = bin2hex($bytes);
                    if (!$this::find()->where(['order_uid' => $order_uid])->exists()) {
                        $this->order_uid = $order_uid;
                        break;
                    }
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage());
            }
        }

        return parent::beforeSave($insert);
    }

    public function getOrderUid()
    {
        return $this->id . '_' . $this->order_uid;
    }

    public static function findByUid($uid)
    {
        // В первую очередь для пользователей типа set_<номер>
        if ($user = User::findOne(['order_uid' => $uid])) {
            return $user;
        }

        $uidParts = explode('_', $uid);

        if (count($uidParts) != 2) {
            Yii::error('Wrong UID: ' . $uid);
            return false;
        }

        return User::findOne(['id' => $uidParts[0], 'order_uid' => $uidParts[1]]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['login_lat_long'] = \Yii::t('app', 'Longitude, latitude on login');

        return $labels;
    }

    /**
     * Gets query for [[CoWorkers]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerQuery
     */
    public function getCoWorkers()
    {
        return $this->hasMany(CoWorker::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Components]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getComponents()
    {
        return $this->hasMany(Component::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ComponentSwitchGroups]].
     *
     * @return \yii\db\ActiveQuery|ComponentSwitchGroupQuery
     */
    public function getComponentSwitchGroups()
    {
        return $this->hasMany(ComponentSwitchGroup::className(), ['user_id' => 'id']);
    }

//    /**
//     * Gets query for [[ShopOrders]].
//     *
//     * @return \yii\db\ActiveQuery|ShopOrderQuery
//     */
//    public function getShopOrders()
//    {
//        return $this->hasMany(ShopOrder::className(), ['user_id' => 'id']);
//    }

    /**
     * Gets query for [[ShopOrderSignal]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderSignalQuery
     */
    public function getShopOrderSignal()
    {
        return $this->hasOne(ShopOrderSignal::className(), ['user_id' => 'id']);
    }

    /**
     * @return UserQuery
     * Gets query for [[ShopOrderStatuses]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderStatusQuery
     */
    public function getShopOrderStatuses()
    {
        return $this->hasMany(ShopOrderStatus::className(), ['user_id' => 'id']);
    }

    /**
     * @return UserQuery
     * Gets query for [[ShopOrderUsers]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderUserQuery
     */
    public function getShopOrderUsers()
    {
        return $this->hasMany(ShopOrderUser::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ShopOrders0]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderQuery
     */
    public function getShopOrders0()
    {
        return $this->hasMany(ShopOrder::className(), ['id' => 'shop_order_id'])->viaTable('shop_order_user', ['user_id' => 'id']);
    }

    public function getIconImageSize($maxWidth = false, $maxHeight = false)
    {
        $size = ['width' => 0, 'height' => 0];
        if ($this->profile->icon_image_path) {
            $imgFilePath = UploadProfileIconImageForm::rootImagePlacePath() . '/' . ltrim($this->profile->icon_image_path,
                    DIRECTORY_SEPARATOR);
            if (is_file($imgFilePath)) {
                if ($gimSize = getimagesize($imgFilePath)) {
                    $size['width'] = $gimSize[0];
                    $size['height'] = $gimSize[1];
                }
            }
        }

        if ($maxWidth && $size['width'] > $maxWidth) {
            $size['height'] *= $maxWidth / $size['width'];
            $size['width'] = $maxWidth;
        }

        if ($maxHeight && $size['height'] > $maxHeight) {
            $size['width'] *= $maxHeight / $size['height'];
            $size['height'] = $maxHeight;
        }

        return $size;
    }

    /**
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(static::class);
    }
}
