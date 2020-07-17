<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[CoWorker]].
 *
 * @see CoWorker
 */
class CoWorkerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CoWorker[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CoWorker|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
