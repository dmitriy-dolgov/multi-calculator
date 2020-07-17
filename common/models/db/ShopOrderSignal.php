<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "shop_order_signal".
 *
 * @property int $user_id
 * @property string|null $emails JSON - список email куда сигналить о новом заказе
 * @property string|null $phones JSON - список телефонов куда отправлять SMS о новом заказе
 *
 * @property User $user
 */
class ShopOrderSignal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order_signal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['emails', 'phones'], 'string'],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'emails' => Yii::t('app', 'Emails'),
            'phones' => Yii::t('app', 'Phones'),
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
     * {@inheritdoc}
     * @return ShopOrderSignalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopOrderSignalQuery(get_called_class());
    }
}
