<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \Da\User\Form\RegistrationForm $model
 * @var \Da\User\Model\User $user
 * @var \Da\User\Module $module
 */

$this->title = Yii::t('usuario', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.contract-modal .modal-header {
    color: #333;
}
.contract-modal .modal-body {
    color: #2a2a2a;
}
CSS
);

?>
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(
                    [
                        'id' => $model->formName(),
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                    ]
                ); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'username') ?>

                <?php if ($module->generatePasswords === false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

                <?php if ($module->enableGdprCompliance): ?>
                    <?= $form->field($model, 'gdpr_consent')->checkbox(['value' => 1]) ?>
                <?php endif ?>

                <div class="container" style="margin-bottom: 15px; margin-right: 15px;">
                <span class="button-checkbox">
                    <button type="button" class="btn" data-color="primary"><?= Yii::t('app', 'Согласен') ?></button>
                    <input type="checkbox" class="hidden"/>
                </span>
                    <?= Html::a(Yii::t('app', 'Please confirm your agreement with contract.',
                        ['contract' => 'contract']), '#', [
                        'style' => 'margin-left: 15px',
                        'data-toggle' => 'modal',
                        'data-target' => '#contractModal',
                    ]) ?>
                </div>

                <?= Html::submitButton(Yii::t('usuario', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>Ю
            </div>

        </div>
        <p class="text-center">
            <?= Html::a(Yii::t('usuario', 'Already registered? Sign in!'), ['/user/security/login']) ?>
        </p>
    </div>
</div>

<div id="contractModal" class="contract-modal modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Contract') ?></h4>
            </div>
            <div class="modal-body">
                <?= \common\models\db\Texts::findOne('registering-customer-agreement')->content ?>
            </div>
        </div>

    </div>
</div>
