<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ShopOrderComponents]].
 *
 * @see ShopOrderComponents
 */
class ShopOrderComponentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ShopOrderComponents[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ShopOrderComponents|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
