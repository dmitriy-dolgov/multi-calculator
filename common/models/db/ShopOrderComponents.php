<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "shop_order_components".
 *
 * @property int $id
 * @property int $shop_order_id
 * @property int|null $component_id
 * @property string|null $name
 * @property string|null $short_name
 * @property int|null $amount
 * @property float|null $order_price
 * @property float|null $order_price_discount
 *
 * @property Component $component
 * @property ShopOrder $shopOrder
 */
class ShopOrderComponents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order_components';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_order_id'], 'required'],
            [['shop_order_id', 'component_id', 'amount'], 'integer'],
            [['order_price', 'order_price_discount'], 'number'],
            [['name', 'short_name'], 'string', 'max' => 255],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Component::className(), 'targetAttribute' => ['component_id' => 'id']],
            [['shop_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopOrder::className(), 'targetAttribute' => ['shop_order_id' => 'id']],
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
            'component_id' => Yii::t('app', 'Component ID'),
            'name' => Yii::t('app', 'Name'),
            'short_name' => Yii::t('app', 'Short Name'),
            'amount' => Yii::t('app', 'Amount'),
            'order_price' => Yii::t('app', 'Order Price'),
            'order_price_discount' => Yii::t('app', 'Order Price Discount'),
        ];
    }

    /**
     * Gets query for [[Component]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getComponent()
    {
        return $this->hasOne(Component::className(), ['id' => 'component_id']);
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
     * {@inheritdoc}
     * @return ShopOrderComponentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopOrderComponentsQuery(get_called_class());
    }
}
