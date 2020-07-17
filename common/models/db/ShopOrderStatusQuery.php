<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ShopOrderStatus]].
 *
 * @see ShopOrderStatus
 */
class ShopOrderStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ShopOrderStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ShopOrderStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
