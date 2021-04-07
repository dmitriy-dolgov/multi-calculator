<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[CourierData]].
 *
 * @see CourierData
 */
class CourierDataQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CourierData[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CourierData|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
