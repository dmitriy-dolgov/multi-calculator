<?php

namespace common\components;

use Yii;
use common\models\db\User;
use yii\base\Component;
use yii\web\BadRequestHttpException;

class Randomise extends Component
{
    /**
     * TODO: Обязательно доделать.
     *
     * @return array
     */
    public static function getName($prefix = ''): array
    {
        /*if (!$prefix) {
            $prefix = 'NoName_';
        }*/

        $name = $prefix . rand(10, 99999);

        return $name;
    }

    public static function getPhone() {

        return self::getName() . '@' . self::getName() . '.com';
    }
}
