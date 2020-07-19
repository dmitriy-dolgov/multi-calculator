<?php

namespace common\models\db\user;

use Da\User\Model\User as BaseUser;

class UserCustomer extends BaseUser
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_customer}}';
    }
}
