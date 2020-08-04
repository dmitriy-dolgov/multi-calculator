<?php

namespace common\models\db;

use common\helpers\Setup;
use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "co_worker".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $birthday
 * @property string|null $co_worker_function
 * @property string|null $description
 * @property string|null $worker_site_uid Уникальный ID для доступа к панели с доступными ползьователю функциями
 *
 * @property CoWorkerFunction $coWorkerFunction
 * @property User $user
 * @property ShopOrderStatus[] $shopOrderStatuses
 */
class CoWorker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'co_worker';
    }

    public function behaviors() {
        return [
            //TODO: удалить из контроллера линк
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                //TODO: возможно, добавить поле чтобы знать кто изменял в последний раз
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['birthday'], 'safe'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['co_worker_function'], 'string', 'max' => 70],
            [['worker_site_uid'], 'string', 'max' => 20],
            [['co_worker_function'], 'exist', 'skipOnError' => true, 'targetClass' => CoWorkerFunction::className(), 'targetAttribute' => ['co_worker_function' => 'id']],
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
            'name' => Yii::t('app-alt-1', 'Name'),
            'birthday' => Yii::t('app', 'Birthday'),
            'co_worker_function' => Yii::t('app', 'Co-worker function'),
            'description' => Yii::t('app', 'Description'),
            'worker_site_uid' => Yii::t('app', 'Worker Site Unique ID'),
        ];
    }

    /**
     * Gets query for [[CoWorkerFunction]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerFunctionQuery
     */
    public function getCoWorkerFunction()
    {
        return $this->hasOne(CoWorkerFunction::className(), ['id' => 'co_worker_function']);
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
     * Gets query for [[ShopOrderStatuses]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderStatusQuery
     */
    public function getShopOrderStatuses()
    {
        return $this->hasMany(ShopOrderStatus::className(), ['accepted_by' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CoWorkerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CoWorkerQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        $this->birthday = Setup::convert($this->birthday, 'datetime');

        return parent::beforeSave($insert);
    }
}
