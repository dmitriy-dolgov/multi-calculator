<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[CourierImages]].
 *
 * @see CourierImages
 */
class CourierImagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CourierImages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CourierImages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
