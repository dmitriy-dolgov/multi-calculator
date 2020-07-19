<?php

namespace common\models\db\user;

use Da\User\Model\Token as BaseProfile;

class TokenCustomer extends BaseProfile
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%token_customer}}';
    }
}
