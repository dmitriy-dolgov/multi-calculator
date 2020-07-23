<?php

namespace common\models\html;

use common\helpers\Internationalization;
use common\models\db\Component;
use yii\base\BaseObject;
use yii\helpers\Html;

class ComponentHtml extends BaseObject
{
    public static function getPriceCaption(Component $component)
    {
        return !empty($component->price)
            ? Internationalization::getPriceCaption($component->price)
            : Html::encode(\Yii::t('app', 'For free'));
    }
}
