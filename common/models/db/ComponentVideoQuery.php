<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ComponentVideo]].
 *
 * @see ComponentVideo
 */
class ComponentVideoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ComponentVideo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ComponentVideo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
