<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ComponentSwitchGroup]].
 *
 * @see ComponentSwitchGroup
 */
class ComponentSwitchGroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ComponentSwitchGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ComponentSwitchGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
