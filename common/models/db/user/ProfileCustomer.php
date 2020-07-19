<?php

namespace common\models\db\user;

use Da\User\Model\Profile as BaseProfile;

class ProfileCustomer extends BaseProfile
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile_customer}}';
    }
}
