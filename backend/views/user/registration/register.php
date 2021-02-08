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
/*.contract-modal .modal-header {
    color: #333;
}
.contract-modal .modal-body {
    color: #2a2a2a;
}
#contractModal {
    padding-left: 0;
}*/

#RegistrationForm {
    color: #2a2a2a;
}
.modal-content {
    color: black;
}
CSS
);

$formName = $model->formName();

$this->registerJs(<<<JS
$('form#{$formName}').submit(function(e) {
    e.stopImmediatePropagation(); // this is required in some cases
    if (!$('.confirm-contract-agreement').prop('checked')) {
       alert('Пожалуйста, подтвердите своё согласие с договором!');
       return false;
    }
});

JS
);

?>
<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(
                    [
                        'id' => $formName,
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
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

                <div class="form-group">
                    <div class="container">
                    <span class="button-checkbox" style="margin-right: 15px;">
                        <button type="button" class="btn" data-color="primary"><?= Yii::t('app', 'Согласен') ?></button>
                        <input type="checkbox" class="hidden confirm-contract-agreement"/>
                    </span>
                        <?= Yii::t('app_c',
                            'Please confirm your agreement with {contract}.',
                            [
                                'contract' => Html::a(Yii::t('app_i-c_c', 'contract'), '#', [
                                    'class' => 'elem-show-contract',
                                    //'style' => 'margin-left: 15px',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#contractModal',
                                ])
                            ]) ?>
                    </div>
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
                <h4 class="modal-title"><?= Yii::t('app_c', 'Contract') ?></h4>
            </div>
            <div class="modal-body">
                <?= \common\models\db\Texts::findOne('registering-customer-agreement')->content ?>
            </div>
        </div>

    </div>
</div>
