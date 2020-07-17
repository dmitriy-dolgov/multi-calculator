<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[Component]].
 *
 * @see Component
 */
class ComponentQuery extends TrackedEntity
{
    public function active()
    {
        //return $this->andWhere('[[status]]=1');
        return $this->andWhere([
            'or',
            ['disabled' => 0],
            ['disabled' => null],
        ]);
    }

    public function forOrder()
    {
        return $this->andWhere('[[amount]]<>0')->active();
    }

    /**
     * {@inheritdoc}
     * @return Component[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Component|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
