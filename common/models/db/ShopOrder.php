<?php

namespace common\models\db;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "shop_order".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $order_uid
 * @property string|null $created_at
 * @property string|null $deliver_address Куда доставить заказ
 * @property string|null $deliver_customer_name
 * @property string|null $deliver_phone
 * @property string|null $deliver_email
 * @property string|null $deliver_comment
 * @property string|null $deliver_required_time_begin Время когда надо доставить заказ - начало
 * @property string|null $deliver_required_time_end Время когда надо доставить заказ - конец
 *
 * @property User $user
 * @property ShopOrderComponents[] $shopOrderComponents
 * @property ShopOrderStatus[] $shopOrderStatuses
 * @property ShopOrderUser[] $shopOrderUsers
 * @property User[] $users
 */
class ShopOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['created_at', 'deliver_required_time_begin', 'deliver_required_time_end'], 'safe'],
            [['deliver_address', 'deliver_comment'], 'string'],
            [['order_uid'], 'string', 'max' => 50],
            [['deliver_customer_name', 'deliver_email'], 'string', 'max' => 255],
            [['deliver_phone'], 'string', 'max' => 40],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'order_uid' => Yii::t('app', 'Order UID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deliver_address' => Yii::t('app', 'Delivery Address'),
            'deliver_customer_name' => Yii::t('app', 'Delivery Customer Name'),
            'deliver_phone' => Yii::t('app', 'Delivery Customer Phone'),
            'deliver_email' => Yii::t('app', 'Delivery Customer Email'),
            'deliver_comment' => Yii::t('app', 'Delivery Customer Comment'),
            'deliver_required_time_begin' => Yii::t('app', 'Delivery Time - Start'),
            'deliver_required_time_end' => Yii::t('app', 'Delivery Time - End'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|Da\User\Query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[ShopOrderComponents]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderComponentsQuery
     */
    public function getShopOrderComponents()
    {
        return $this->hasMany(ShopOrderComponents::className(), ['shop_order_id' => 'id']);
    }

    /**
     * Gets query for [[ShopOrderStatuses]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderStatusQuery
     */
    public function getShopOrderStatuses()
    {
        return $this->hasMany(ShopOrderStatus::className(), ['shop_order_id' => 'id']);
    }

    /**
     * Gets query for [[ShopOrderUsers]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderUserQuery
     */
    public function getShopOrderUsers()
    {
        return $this->hasMany(ShopOrderUser::className(), ['shop_order_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('shop_order_user', ['shop_order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ShopOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopOrderQuery(get_called_class());
    }

    public function getOrderUrl()
    {
        return Url::to(['/vendor/order-info', 'uid' => $this->order_uid], true);
    }

    public function getTotalPrice()
    {
        $price = 0;

        foreach ($this->shopOrderComponents as $component) {
            $price += $component->order_price;
        }

        return $price;
    }

    public function getAmountOfUsers()
    {
        return $this->getUsers()->count();
    }

    //TODO: refactoring - см. где используется
    public function ifBelongsToUser($userId)
    {
        return true;
        foreach ($this->users as $user) {
            if ($user->id == $userId) {
                return true;
            }
        }

        return false;
    }
}
