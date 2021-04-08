<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "courier_data".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name_of_courier
 * @property string|null $description_of_courier
 * @property string|null $photo_of_courier
 * @property string|null $courier_in_move Название изображения курьера в движении
 * @property string|null $courier_is_waiting Название изображения курьера в ожидании - например ждет клиента
 * @property int|null $velocity Средняя скорость курьера - км/час
 * @property int $priority Приоритет при любом статусе - например при случайном выборе - чем выше тем больше.
 *
 * @property User $user
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
            [['user_id', 'velocity', 'priority'], 'integer'],
            [['description_of_courier'], 'string'],
            [['name_of_courier', 'photo_of_courier', 'courier_in_move', 'courier_is_waiting'], 'string', 'max' => 255],
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
            'name_of_courier' => Yii::t('app', 'Name Of Courier'),
            'description_of_courier' => Yii::t('app', 'Description Of Courier'),
            'photo_of_courier' => Yii::t('app', 'Photo Of Courier'),
            'courier_in_move' => Yii::t('app', 'Courier In Move'),
            'courier_is_waiting' => Yii::t('app', 'Courier Is Waiting'),
            'velocity' => Yii::t('app', 'Velocity'),
            'priority' => Yii::t('app', 'Priority'),
        ];
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
     * @return CourierDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CourierDataQuery(get_called_class());
    }
}
