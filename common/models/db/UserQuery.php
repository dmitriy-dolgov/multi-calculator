<?php

namespace common\models\db;

use Da\User\Query\UserQuery as BaseUserQuery;

class UserQuery extends BaseUserQuery
{
    public function activeAcceptOrders()
    {


        return $this
            ->joinWith(['profile'])
            ->andWhere(['profile.status' => 'active-accept-orders']);
    }
}
