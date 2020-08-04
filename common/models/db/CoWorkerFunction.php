<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "co_worker_function".
 *
 * @property string $id
 * @property string|null $name
 * @property string|null $description
 *
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
     * Gets query for [[CoWorkers]].
     *
     * @return \yii\db\ActiveQuery|CoWorkerQuery
     */
    public function getCoWorkers()
    {
        return $this->hasMany(CoWorker::className(), ['co_worker_function' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CoWorkerFunctionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CoWorkerFunctionQuery(get_called_class());
    }
}
