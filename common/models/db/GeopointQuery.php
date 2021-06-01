<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[Geopoint]].
 *
 * @see Geopoint
 */
class GeopointQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Geopoint[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Geopoint|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
