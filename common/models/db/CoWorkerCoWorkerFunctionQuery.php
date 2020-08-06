<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[CoWorkerCoWorkerFunction]].
 *
 * @see CoWorkerCoWorkerFunction
 */
class CoWorkerCoWorkerFunctionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CoWorkerCoWorkerFunction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CoWorkerCoWorkerFunction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
