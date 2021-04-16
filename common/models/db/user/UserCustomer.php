<?php

/**
 * Похоже, этот класс предназначен для покупателей пиццы.
 * Пока, похоже, не задействован пока.
 *
 */

namespace common\models\db\user;

use common\models\db\User as BaseUser;


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
