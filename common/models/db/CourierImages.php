<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "courier_images".
 *
 * @property int $id
 * @property string|null $run Название изображения "Курьер в движении"
 * @property string|null $wait Название изображения "Курьер в ожидании покупателя чтобы отдать пиццу"
 * @property string|null $disabled_at Когда изображение стало неактивным - действует как расширенный датой boolean
 */
class CourierImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courier_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['disabled_at', 'filter', 'filter' => function($value) {
                if (empty($value)) {
                    return null;
                }
                return date('Y-m-d H:i:s');
            }],
            [['run', 'wait'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'run' => Yii::t('app', 'В пути'),
            'wait' => Yii::t('app', 'Ожидает'),
            'disabled_at' => Yii::t('app', 'Когда отключен'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CourierImagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CourierImagesQuery(get_called_class());
    }
}
