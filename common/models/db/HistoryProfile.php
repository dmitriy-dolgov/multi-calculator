<?php

namespace common\models\db;

use common\models\db\user\UserCustomer;
use Yii;

/**
 * This is the model class for table "history_profile".
 *
 * @property int $id
 * @property int|null $user_id Инициатор типа продавец
 * @property int|null $user_customer_id Инициатор типа покупатель
 * @property string|null $name Короткое название
 * @property string|null $about Информация для чего создавался проект
 * @property string|null $server_info Данные на стороне сервера в произвольном формате json
 * @property string|null $remote_info Данные на стороне пользователя в произвольном формате json
 * @property string|null $created_at
 * @property string|null $changed_at
 * @property string|null $deleted_at
 *
 * @property UserCustomer $userCustomer
 * @property User $user
 */
class HistoryProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'history_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_customer_id'], 'integer'],
            [['about', 'server_info', 'remote_info'], 'string'],
            [['created_at', 'changed_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['user_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserCustomer::className(), 'targetAttribute' => ['user_customer_id' => 'id']],
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
            'user_customer_id' => Yii::t('app', 'User Customer ID'),
            'name' => Yii::t('app', 'Name'),
            'about' => Yii::t('app', 'About'),
            'server_info' => Yii::t('app', 'Server Info'),
            'remote_info' => Yii::t('app', 'Remote Info'),
            'created_at' => Yii::t('app', 'Created At'),
            'changed_at' => Yii::t('app', 'Changed At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Gets query for [[UserCustomer]].
     *
     * @return \yii\db\ActiveQuery|UserCustomerQuery
     */
    public function getUserCustomer()
    {
        return $this->hasOne(UserCustomer::className(), ['id' => 'user_customer_id']);
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
     * @return HistoryProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HistoryProfileQuery(get_called_class());
    }
}
