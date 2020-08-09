<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[CoWorkerDeclineCause]].
 *
 * @see CoWorkerDeclineCause
 */
class CoWorkerDeclineCauseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CoWorkerDeclineCause[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CoWorkerDeclineCause|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
