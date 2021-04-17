<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[UserVirtual]].
 *
 * @see UserVirtual
 */
class UserVirtualQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserVirtual[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserVirtual|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
