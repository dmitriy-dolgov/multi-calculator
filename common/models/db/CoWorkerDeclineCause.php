<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "co_worker_decline_cause".
 *
 * @property int $id
 * @property int|null $co_worker_id
 * @property string $cause
 * @property int|null $order
 *
 * @property CoWorker $coWorker
 */
class CoWorkerDeclineCause extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'co_worker_decline_cause';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['co_worker_id', 'order'], 'integer'],
            [['cause'], 'required'],
            [['cause'], 'string'],
            [['co_worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoWorker::className(), 'targetAttribute' => ['co_worker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'co_worker_id' => Yii::t('app', 'Co Worker ID'),
            'cause' => Yii::t('app', 'Cause'),
            'order' => Yii::t('app', 'Sort Order'),
        ];
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
     * @return CoWorkerDeclineCauseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CoWorkerDeclineCauseQuery(get_called_class());
    }
}
