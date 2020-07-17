<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "shop_order_status".
 *
 * @property int $id
 * @property int|null $shop_order_id
 * @property int|null $user_id
 * @property string|null $type Тип заказа - принят к исполнению, отложен, начал готовиться, в процессе доставки, отменен, завершен и т. д.
 * @property string|null $accepted_at Время назначения статуса
 * @property int|null $accepted_by Сотрудник назначивший статус
 * @property string|null $data Та или иная дополнительная информация (например, заказ создан для одной пиццерии или для нескольких)
 * @property string|null $description Описание, которое, возможно, захочет добавить тот кто принял заказ или заказчик
 *
 * @property CoWorker $acceptedBy
 * @property ShopOrder $shopOrder
 * @property User $user
 */
class ShopOrderStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_order_id', 'user_id', 'accepted_by'], 'integer'],
            [['accepted_at'], 'safe'],
            [['data', 'description'], 'string'],
            [['type'], 'string', 'max' => 255],
            [
                ['accepted_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CoWorker::className(),
                'targetAttribute' => ['accepted_by' => 'id']
            ],
            [
                ['shop_order_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ShopOrder::className(),
                'targetAttribute' => ['shop_order_id' => 'id']
            ],
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
            'shop_order_id' => Yii::t('app', 'Shop Order ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'accepted_at' => Yii::t('app', 'Accepted At'),
            'accepted_by' => Yii::t('app', 'Accepted By'),
            'data' => Yii::t('app', 'Data'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[AcceptedBy]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerQuery
     */
    public function getAcceptedBy()
    {
        return $this->hasOne(CoWorker::className(), ['id' => 'accepted_by']);
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
     * @return ShopOrderStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopOrderStatusQuery(get_called_class());
    }

    public static function getStatusTypeList()
    {
        return [
            'created' => Yii::t('app', 'Created.'),
            // Создано предложение
            'offer-sent-to-customer' => Yii::t('app', 'Offer sent to customer.'),
            // Предложение принято пользователем
            'offer-accepted-by-customer' => Yii::t('app', 'Offer accepted by customer.'),
            // Предложение отменено, потому что клиент выбрал другую пиццерию
            'offer-abandoned-because-customer-selected' => Yii::t('app', 'Offer abandoned because customer selected another pizzeria.'),
            // Заказ отменен пользователем
            'order-cancelled-by-user' => Yii::t('app', 'Order cancelled by user.'),
        ];
    }

    public function setStatus($type)
    {
        if (!isset(self::getStatusTypeList()[$type])) {
            throw new \LogicException('No such status: ' . $type);
        }
        $this->type = $type;
    }

    public function getStatusName()
    {
        return self::getStatusTypeList()[$this->type];
    }

    //TODO: рассмотреть нужна ли эта функция
    public function getHandleStatusHtml()
    {
        $html = '';

        if ($this->type == 'created') {
            $html .= <<<HTML
<button onclick=""></button>
HTML;
        }

        return $html;
    }
}
