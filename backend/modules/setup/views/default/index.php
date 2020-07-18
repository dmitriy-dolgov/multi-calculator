<?php

use yii\helpers\Url;

?>
<div class="setup-default-index">
    <h1><?= Yii::t('app', 'Maintenance') ?></h1>
    <hr>
    <?php //TODO: после создания шаблонов добавить это в описание ?>
    <p>Здесь настраиваются элементы и поведение вашего сайта, а также заполняются данные для того, чтобы ваши клиенты и
        мы могли связаться с вами.</p>
    <p>Перейдите на страницу <span class="link-for-menu"><a href="<?= Url::to(['/setup/component']) ?>"><i
                        class="icon fa fa-pie-chart"></i><?= Yii::t('app',
                    'Components') ?></a></span> для того чтобы настроить элементы из которых состоит ваш продукт.</p>
    <p>В меню <span class="link-for-menu"><i class="icon fa fa-user-o"></i><?= Yii::t('app',
                'User') ?></span> доступны следующие элементы:
    <ul>
        <li><span class="link-for-menu"><a href="<?= Url::to(['/user/settings/account']) ?>"><?= Yii::t('app',
                        'Account') ?></a></span> - данные учетной записи.
        </li>
        <li><span class="link-for-menu"><a href="<?= Url::to(['/user/settings/profile']) ?>"><?= Yii::t('app',
                        'Profile') ?></a></span> - информация о вас или вашей компании.
        </li>
    </ul>

    </p>
</div>
