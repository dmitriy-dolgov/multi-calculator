<?php

namespace common\helpers;

use Yii;

class Web
{

    /**
     * TODO: оптимизовать чтобы результат кешировался.
     *
     * @return bool
     */
    public static function isLocal()
    {
        // Похоже это консоль
        if (!isset($_SERVER['SERVER_NAME'])) {
            return false;
        }

        $domainParts = explode('.', $_SERVER['SERVER_NAME']);
        return end($domainParts) == 'local';
    }

    public static function getUrlToCustomerSite()
    {
        return Yii::$app->params['domain-customer-schema'] . '://' . Yii::$app->params['domain-customer'];
    }
}
