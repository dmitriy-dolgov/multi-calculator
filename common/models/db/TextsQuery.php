<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[Texts]].
 *
 * @see Texts
 */
class TextsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Texts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Texts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
