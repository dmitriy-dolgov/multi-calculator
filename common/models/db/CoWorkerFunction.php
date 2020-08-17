<?php

namespace common\models\db;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "co_worker_function".
 *
 * @property string $id
 * @property string|null $name
 * @property string|null $description
 *
 * @property CoWorkerCoWorkerFunction[] $coWorkerCoWorkerFunctions
 * @property CoWorker[] $coWorkers
 */
class CoWorkerFunction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'co_worker_function';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['description'], 'string'],
            [['id'], 'string', 'max' => 70],
            [['name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[CoWorkerCoWorkerFunctions]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerCoWorkerFunctionQuery
     */
    public function getCoWorkerCoWorkerFunctions()
    {
        return $this->hasMany(CoWorkerCoWorkerFunction::className(), ['co_worker_function_id' => 'id']);
    }

    /**
     * Gets query for [[CoWorkers]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerQuery
     */
    public function getCoWorkers()
    {
        return $this->hasMany(CoWorker::className(), ['id' => 'co_worker_id'])->viaTable('co_worker_co_worker_function', ['co_worker_function_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CoWorkerFunctionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CoWorkerFunctionQuery(get_called_class());
    }

    public function getCoWorkerFunctionClassById()
    {
        //$className = $id ?: 'Orders';
        $className = ucfirst(preg_replace('/[^a-zA-Z0-9]/', '', $this->id));
        $className = 'common\models\shop_order\ShopOrder' . $className;

        return $className;
    }
}
