<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[Shop]].
 *
 * @see Shop
 */
class ShopQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Shop[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Shop|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
