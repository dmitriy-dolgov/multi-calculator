<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user_virtual".
 *
 * @property int $id
 *
 * @property UserUserVirtual[] $userUserVirtuals
 * @property User[] $users
 */
class UserVirtual extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_virtual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
        ];
    }

    /**
     * Gets query for [[UserUserVirtuals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserUserVirtuals()
    {
        return $this->hasMany(UserUserVirtual::className(), ['user_virtual_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_user_virtual', ['user_virtual_id' => 'id']);
    }
}
