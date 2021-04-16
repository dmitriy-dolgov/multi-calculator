<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user_user_virtual".
 *
 * @property int $user_id
 * @property int $user_virtual_id
 *
 * @property User $user
 * @property UserVirtual $userVirtual
 */
class UserUserVirtual extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_user_virtual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_virtual_id'], 'required'],
            [['user_id', 'user_virtual_id'], 'integer'],
            [['user_id', 'user_virtual_id'], 'unique', 'targetAttribute' => ['user_id', 'user_virtual_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_virtual_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserVirtual::className(), 'targetAttribute' => ['user_virtual_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'user_virtual_id' => Yii::t('app', 'User Virtual ID'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[UserVirtual]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserVirtual()
    {
        return $this->hasOne(UserVirtual::className(), ['id' => 'user_virtual_id']);
    }
}
