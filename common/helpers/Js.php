<?php

namespace app\helpers;

use yii\helpers\Html;

class Js
{
    public static function createJsDataStrings(array $jsStrings)
    {
        $jsCode = '';

        foreach ($jsStrings as $name => $value) {
            $jsCode .= "gl.data['$name'] = " . json_encode(Html::encode($value)) . ";\n";
        }

        return $jsCode;
    }

    /**
     * Same as createJsDataStrings() but no Html::encode()
     *
     * @param array $jsStrings
     * @return string
     */
    public static function createJsDataStringsNE(array $jsStrings)
    {
        $jsCode = '';

        foreach ($jsStrings as $name => $value) {
            $jsCode .= "gl.data['$name'] = " . json_encode($value) . ";\n";
        }

        return $jsCode;
    }
}
