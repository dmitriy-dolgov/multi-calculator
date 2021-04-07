<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "account_template".
 *
 * @property int $id
 * @property int|null $old_user_id Ссылка на сохраненный в архиве аккаунт.
 * @property int|null $new_user_id Ссылка новый созданный аккаунт.
 * @property string|null $name
 * @property string|null $description
 *
 * @property User $newUser
 * @property User $oldUser
 */
class CourierData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['old_user_id', 'new_user_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['new_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['new_user_id' => 'id']],
            [['old_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['old_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'old_user_id' => Yii::t('app', 'Old User ID'),
            'new_user_id' => Yii::t('app', 'New User ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[NewUser]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getNewUser()
    {
        return $this->hasOne(User::className(), ['id' => 'new_user_id']);
    }

    /**
     * Gets query for [[OldUser]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getOldUser()
    {
        return $this->hasOne(User::className(), ['id' => 'old_user_id']);
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
