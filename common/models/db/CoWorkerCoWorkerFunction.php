<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "co_worker_co_worker_function".
 *
 * @property int $co_worker_id
 * @property string $co_worker_function_id
 *
 * @property CoWorkerFunction $coWorkerFunction
 * @property CoWorker $coWorker
 */
class CoWorkerCoWorkerFunction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'co_worker_co_worker_function';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['co_worker_id', 'co_worker_function_id'], 'required'],
            [['co_worker_id'], 'integer'],
            [['co_worker_function_id'], 'string', 'max' => 70],
            [['co_worker_id', 'co_worker_function_id'], 'unique', 'targetAttribute' => ['co_worker_id', 'co_worker_function_id']],
            [['co_worker_function_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoWorkerFunction::className(), 'targetAttribute' => ['co_worker_function_id' => 'id']],
            [['co_worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoWorker::className(), 'targetAttribute' => ['co_worker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'co_worker_id' => Yii::t('app', 'Co Worker ID'),
            'co_worker_function_id' => Yii::t('app', 'Co Worker Function ID'),
        ];
    }

    /**
     * Gets query for [[CoWorkerFunction]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerFunctionQuery
     */
    public function getCoWorkerFunction()
    {
        return $this->hasOne(CoWorkerFunction::className(), ['id' => 'co_worker_function_id']);
    }

    /**
     * Gets query for [[CoWorker]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerQuery
     */
    public function getCoWorker()
    {
        return $this->hasOne(CoWorker::className(), ['id' => 'co_worker_id']);
    }

    /**
     * {@inheritdoc}
     * @return CoWorkerCoWorkerFunctionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CoWorkerCoWorkerFunctionQuery(get_called_class());
    }
}
