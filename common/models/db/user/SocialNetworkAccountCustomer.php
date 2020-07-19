<?php

namespace common\models\db\user;

use Da\User\Model\SocialNetworkAccount as BaseProfile;

class SocialNetworkAccountCustomer extends BaseProfile
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%social_account_customer}}';
    }
}
