<?php

use yii\helpers\Html;

/* @var $componentSets common\models\db\ComponentSet[] */
/* @var $this yii\web\View */

?>

<div class="pizzas-panel-elements-list unwrapped-panel">
    <?= Html::img('/img/ok-btn.svg', ['title' => Yii::t('app', 'Свернуть'), 'class' => 'unwrap-panel__close-button']) ?>
    <div class="header"><?= Yii::t('app', 'Choose your pizza') ?></div>
    <div class="header-tip"><?= Yii::t('app', 'Or create it yourself ⇒') ?></div>
    <hr>
    <?php
    foreach ($componentSets as $cs) {
        if ($cs->components) {
            $compsData = [];
            foreach ($cs->components as $comp) {
                $compsData[] = $comp->createHtmlDataParams();
            }
            //TODO: pz_comp
            echo Html::tag('div', $cs->name, [
                //'class' => 'elem ttt',
                'class' => 'ttt',
                'data-id' => $cs->id,
                'data-name' => $cs->name,
                'data-components' => json_encode($compsData),
                'onclick' => 'gl.functions.unwrapBottom(this);return false;',
            ]);

            //print_r($compsData);exit;
            $pizza20Data = array_merge([
                [
                    'data-id' => 5300,
                    'data-name' => 'Тесто ⌀20см',
                    'data-short_name' => 'Тесто ⌀20см',
                    'data-price' => 200.00,
                    'data-price_discount' => 0,
                    'data-image' => '/img/compo/base_1.jpeg',
                    'data-image-text' => '⌀20',
                    'data-video' => '/video/construct/default.gif',
                    'data-item_select_min' => 1,
                    'data-item_select_max' => '',
                    'data-unit_name' => '',
                    'data-unit_value' => '',
                    'data-unit_value_min' => '',
                    'data-unit_value_max' => '',
                    'data-unit_switch_group_id' => 0,   //2,
                    'data-unit_switch_group_name' => 'Size of pizza',
                ]
            ], $compsData);

            $pizza25Data = array_merge([
                [
                    'data-id' => 5400,
                    'data-name' => 'Тесто ⌀25см',
                    'data-short_name' => 'Тесто ⌀25см',
                    'data-price' => 200.00,
                    'data-price_discount' => 0,
                    'data-image' => '/img/compo/base_1.jpeg',
                    'data-image-text' => '⌀25',
                    'data-video' => '/video/construct/default.gif',
                    'data-item_select_min' => 1,
                    'data-item_select_max' => '',
                    'data-unit_name' => '',
                    'data-unit_value' => '',
                    'data-unit_value_min' => '',
                    'data-unit_value_max' => '',
                    'data-unit_switch_group_id' => 0,   //2,
                    'data-unit_switch_group_name' => 'Size of pizza',
                ]
            ], $compsData);

            $pizza27Data = array_merge([
                [
                    'data-id' => 5500,
                    'data-name' => 'Тесто ⌀27см',
                    'data-short_name' => 'Тесто ⌀27см',
                    'data-price' => 200.00,
                    'data-price_discount' => 0,
                    'data-image' => '/img/compo/base_1.jpeg',
                    'data-image-text' => '⌀27',
                    'data-video' => '/video/construct/default.gif',
                    'data-item_select_min' => 1,
                    'data-item_select_max' => '',
                    'data-unit_name' => '',
                    'data-unit_value' => '',
                    'data-unit_value_min' => '',
                    'data-unit_value_max' => '',
                    'data-unit_switch_group_id' => 0,   //2,
                    'data-unit_switch_group_name' => 'Size of pizza',
                ]
            ], $compsData);

            $conHtml = Html::tag('div', '⌀20см', [
                    'class' => 'elem elem-pi',
                    //'style' => 'display:none',
                    'data-id' => $cs->id,
                    'data-name' => $cs->name,
                    'data-components' => json_encode($pizza20Data),
                ])
                . Html::tag('div', '⌀25см', [
                    'class' => 'elem elem-pi',
                    //'style' => 'display:none',
                    'data-id' => $cs->id,
                    'data-name' => $cs->name,
                    'data-components' => json_encode($pizza25Data),
                ])
                . Html::tag('div', '⌀27см', [
                    'class' => 'elem elem-pi',
                    //'style' => 'display:none',
                    'data-id' => $cs->id,
                    'data-name' => $cs->name,
                    'data-components' => json_encode($pizza27Data),
                ]);

            echo Html::tag('div', $conHtml, [
                'class' => 'elem',
                'style' => 'background-color: #686666;display:none',
                //'data-id' => $cs->id,
                //'data-name' => $cs->name,
                //'data-components' => json_encode($compsData),
                'onclick' => 'return false;',
            ]);
        }
    }
    ?>
</div>