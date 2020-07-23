<?php

namespace common\helpers;

class Internationalization
{
    public static function getCurrencySign()
    {
        return '₽';
    }

    public static function getCurrencyCentCeparator()
    {
        return '.';
    }

    public static function getPriceCaption($price, $currencyType = 'sign')
    {
        $sign = ($currencyType == 'sign') ? self::getCurrencySign() : ' руб.';

        [$dollars, $cents] = explode(self::getCurrencyCentCeparator(), $price);

        return $dollars . self::getCurrencyCentCeparator() . '<sup>' . $cents . '</sup>'
            . ' <span class="c-sign-in-price">' . $sign . '</span>';
    }
}
