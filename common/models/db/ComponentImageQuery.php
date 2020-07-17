<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[ComponentImage]].
 *
 * @see ComponentImage
 */
class ComponentImageQuery extends TrackedEntity
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ComponentImage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ComponentImage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
