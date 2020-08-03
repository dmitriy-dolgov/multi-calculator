<?php

namespace common\models\db;

use common\helpers\Internationalization;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "customer_active_component".
 *
 * @property int $id
 * @property int|null $component_id
 * @property float|null $price_override
 * @property float|null $price_discount_override
 * @property int $amount
 * @property int|null $unit_id
 * @property float|null $unit_value
 * @property float|null $unit_value_min
 * @property float|null $unit_value_max
 *
 * @property Component $component
 * @property Unit $unit
 */
class CustomerActiveComponent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_active_component';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['component_id', 'amount', 'unit_id'], 'integer'],
            [['price_override', 'price_discount_override', 'unit_value', 'unit_value_min', 'unit_value_max'], 'number'],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Component::className(), 'targetAttribute' => ['component_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'component_id' => Yii::t('app', 'Component ID'),
            'price_override' => Yii::t('app', 'Price Override'),
            'price_discount_override' => Yii::t('app', 'Price Discount Override'),
            'amount' => Yii::t('app', 'Amount'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'unit_value' => Yii::t('app', 'Unit Value'),
            'unit_value_min' => Yii::t('app', 'Unit Value Min'),
            'unit_value_max' => Yii::t('app', 'Unit Value Max'),
        ];
    }

    /**
     * Gets query for [[Component]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComponent()
    {
        return $this->hasOne(Component::className(), ['id' => 'component_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    public function getComponentInfoHtml()
    {
        $result = Yii::$app->formatter->nullDisplay;
        if ($this->component) {
            $content = Html::img($this->component->getImageUrl(), [
                'style' => 'width:40px;margin-right:10px;'
            ])
                . Html::encode($this->component->name) . '; &nbps;'
                . Yii::t('app', 'Price: {price}', ['price' => Internationalization::getPriceCaption($this->component->price)]);
            $content = Html::a($content, ['component/view', 'id' => $this->component->id]);
            $result = Html::tag('div', $content, [
                'style' => 'display:flex;',
            ]);
        }
        return $result;
    }
}
