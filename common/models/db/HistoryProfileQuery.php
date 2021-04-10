<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[HistoryProfile]].
 *
 * @see HistoryProfile
 */
class HistoryProfileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return HistoryProfile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return HistoryProfile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
