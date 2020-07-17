<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ShopOrderUser]].
 *
 * @see ShopOrderUser
 */
class ShopOrderUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ShopOrderUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ShopOrderUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
