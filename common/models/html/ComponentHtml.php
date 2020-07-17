<?php

namespace common\models\html;

use app\helpers\Internationalization;
use common\models\db\Component;
use yii\base\BaseObject;
use yii\helpers\Html;

class ComponentHtml extends BaseObject
{
    public static function getPriceCaption(Component $component)
    {
        return Html::encode(!empty($component->price)
            ? Internationalization::getPriceCaption($component->price)
            : \Yii::t('app', 'For free'));
    }
}
