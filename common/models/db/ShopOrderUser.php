<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "shop_order_user".
 *
 * @property int $shop_order_id
 * @property int $user_id
 *
 * @property ShopOrder $shopOrder
 * @property User $user
 */
class ShopOrderUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_order_id', 'user_id'], 'required'],
            [['shop_order_id', 'user_id'], 'integer'],
            [['shop_order_id', 'user_id'], 'unique', 'targetAttribute' => ['shop_order_id', 'user_id']],
            [['shop_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopOrder::className(), 'targetAttribute' => ['shop_order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'shop_order_id' => Yii::t('app', 'Shop Order ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * Gets query for [[ShopOrder]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderQuery
     */
    public function getShopOrder()
    {
        return $this->hasOne(ShopOrder::className(), ['id' => 'shop_order_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return ShopOrderUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopOrderUserQuery(get_called_class());
    }
}
