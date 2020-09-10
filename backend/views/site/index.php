<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Main page');

\hiqdev\assets\flagiconcss\FlagIconCssAsset::register($this);

//Yii::$app->language = 'ru';
/*$languageItem = new cetver\LanguageSelector\items\DropDownLanguageItem([
    'languages' => [
        'en' => '<span class="flag-icon flag-icon-us"></span>',
        'ru' => '<span class="flag-icon flag-icon-ru"></span>',
    ],
    'options' => ['encode' => false],
]);
$languageItem = $languageItem->toArray();
$languageDropdownItems = \yii\helpers\ArrayHelper::remove($languageItem, 'items');
echo \yii\bootstrap\ButtonDropdown::widget([
    'label' => $languageItem['label'],
    'encodeLabel' => false,
    'options' => [
        'class' => 'btn-default',
        'title' => Yii::t('app', 'Language'),
    ],
    'dropdown' => [
        'items' => $languageDropdownItems,
    ],
]);*/

/*$this->registerJs(<<<JS
    $('#wrap').css('padding-top', $('.pane-mobile-switcher').height());
JS
);*/

?>
<?= Html::img(['/img/pizza-logo-white-border.png'], [
    'alt' => Yii::$app->name,
    'class' => 'main-logo',
]) ?>
<div class="main-background">
    <h1 class="main-caption"><?= Yii::t('app', '{name} - Online Pizza Sales System',
            ['name' => Yii::$app->name]) ?></h1>
    <?= \common\widgets\Alert::widget() ?>
    <ul>
        <li>Зарегистрируйтесь в системе</li>
        <li>Установите ингридиенты для пиццы</li>
        <li>При необходимости, можете установить исполнителей</li>
        <li>Встройте html код формы заказа на ваш сайт</li>
        <li>Тестируйте и используйте форму заказа</li>
        <li>Отслеживайте историю заказов</li>
        <li>Оформите VIP-подписку чтобы убрать рекламу и получить 24/7 поддержку</li>
    </ul>
</div>
