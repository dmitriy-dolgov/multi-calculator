<?php

namespace app\helpers;

class Internationalization
{
    public static function getCurrencySign()
    {
        return '₽';
    }

    public static function getPriceCaption($price, $currencyType = 'sign')
    {
        return $price . ' ' . (($currencyType == 'sign') ? self::getCurrencySign() : ' руб.');
    }
}
