<?php

namespace common\models\db;

/**
 * This is the ActiveQuery class for [[AccountTemplate]].
 *
 * @see AccountTemplate
 */
class AccountTemplateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AccountTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AccountTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
