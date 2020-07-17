<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ShopOrderSignal]].
 *
 * @see ShopOrderSignal
 */
class ShopOrderSignalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ShopOrderSignal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ShopOrderSignal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
