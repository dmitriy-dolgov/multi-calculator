<?php

namespace common\helpers;

use Yii;

class Web
{
    public static function isLocal()
    {
        $domainParts = explode('.', $_SERVER['SERVER_NAME']);
        return end($domainParts) == 'local';
    }

    public static function getUrlToCustomerSite()
    {
        return Yii::$app->params['domain-customer-schema'] . '://' . Yii::$app->params['domain-customer'];
    }
}
