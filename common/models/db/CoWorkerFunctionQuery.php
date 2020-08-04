<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[CoWorkerFunction]].
 *
 * @see CoWorkerFunction
 */
class CoWorkerFunctionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CoWorkerFunction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CoWorkerFunction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
