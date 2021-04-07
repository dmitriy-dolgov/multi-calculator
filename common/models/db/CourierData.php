<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "courier_data".
 *
 * @property int $id
 * @property string|null $name_of_courier
 * @property string|null $description_of_courier
 * @property string|null $photo_of_courier
 * @property string|null $courier_in_move Название изображения курьера в движении
 * @property string|null $courier_is_waiting Название изображения курьера в ожидании - например ждет клиента
 * @property int|null $velocity Средняя скорость курьера - км/час
 * @property int $priority Приоритет при любом статусе - например при случайном выборе - чем выше тем больше.
 */
class CourierData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courier_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description_of_courier'], 'string'],
            [['velocity', 'priority'], 'integer'],
            [['name_of_courier', 'photo_of_courier', 'courier_in_move', 'courier_is_waiting'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_of_courier' => Yii::t('app', 'Имя курьера'),
            'description_of_courier' => Yii::t('app', 'Описание курьера'),
            'photo_of_courier' => Yii::t('app', 'Фото курьера'),
            'courier_in_move' => Yii::t('app', 'Курьер в движении'),
            'courier_is_waiting' => Yii::t('app', 'Курьер в ожидании'),
            'velocity' => Yii::t('app', 'Скорость'),
            'priority' => Yii::t('app', 'Приоритет'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CourierDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CourierDataQuery(get_called_class());
    }
}
