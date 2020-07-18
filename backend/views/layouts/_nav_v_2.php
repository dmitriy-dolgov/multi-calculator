<?php

use \yii\helpers\Url;
use \yii\helpers\Html;

?>
<ul class="main-menu" role="navigation">
    <li class="men-el setup" title="<?= Yii::t('app', 'Set Up') ?>"><a class="icon-cogs"
                                                                       href="<?= Url::to(['/setup']) ?>"
                                                                       target="_blank"></a></li>
    <li class="men-el about" title="<?= Yii::t('app', 'About') ?>"><a class="icon-question"
                                                                            href="<?= Url::to(['/site/about']) ?>"></a>
    </li>
    <li class="men-el contact" title="<?= Yii::t('app', 'Send us a message') ?>"><a class="icon-mail2"
                                                                                    href="<?= Url::to(['/site/contact']) ?>"></a>
    </li>

    <?php /*
    <li class="men-el home" title="<?= Yii::t('app', 'Home') ?>"><a class="icon-office"
                                                                    href="<?= Url::to(['/']) ?>"></a></li>
   */
    ?>

    <?php if (Yii::$app->user->isGuest): ?>
        <div class="reg-in-up">
            <li class="men-el sign-in" title="<?= Yii::t('app', 'Sign In') ?>"><a class="icon-enter"
                                                                                  href="<?= Url::to(['/user/security/login']) ?>"></a>
            </li>
            <li class="men-el sign-up" title="<?= Yii::t('app', 'Sign Up') ?>"><a class="icon-handshake-o"
                                                                                  href="<?= Url::to(['/user/registration/register']) ?>"></a>
            </li>
        </div>
    <?php else: ?>
        <?= Html::beginForm(['/user/security/logout'], 'post',
            ['id' => 'main-menu-logout', 'style' => 'visibility: collapse']) . Html::endForm() ?>
        <li class="men-el exit"
            title="<?= Yii::t('app', 'Sign Out') . ' (' . Yii::$app->user->identity->username . ')' ?>"><a
                    class="icon-exit"
                    href="#" onclick="javascript:$('#main-menu-logout').submit();return false;"></a></li>
    <?php endif; ?>
</ul>