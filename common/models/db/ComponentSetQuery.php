<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ComponentSet]].
 *
 * @see ComponentSet
 */
class ComponentSetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ComponentSet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ComponentSet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
