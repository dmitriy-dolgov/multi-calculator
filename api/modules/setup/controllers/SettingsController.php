<?php

namespace api\modules\setup\controllers;

use common\models\UploadProfileIconImageForm;
use Da\User\Controller\SettingsController as BaseController;
use Da\User\Event\ProfileEvent;
use Da\User\Event\UserEvent;
use Da\User\Model\Profile;
use Da\User\Validator\AjaxRequestModelValidator;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class SettingsController extends BaseController
{
    public function behaviors()
    {
        $data = parent::behaviors();
        $data['access']['rules'] = [
            [
                'allow' => true,
                'actions' => [
                    'profile',
                    'account',
                    'export',
                    'networks',
                    'privacy',
                    'gdpr-consent',
                    'gdpr-delete',
                    'disconnect',
                    'delete',
                    'two-factor',
                    'two-factor-enable',
                    'two-factor-disable',
                    'image-delete',
                ],
                'roles' => ['@'],
            ],
            [
                'allow' => true,
                'actions' => ['confirm'],
                'roles' => ['?', '@'],
            ],
        ];

        return $data;
    }

    public function actionProfile()
    {
        $profile = $this->profileQuery->whereUserId(Yii::$app->user->identity->getId())->one();

        if ($profile === null) {
            $profile = $this->make(Profile::class);
            $profile->link('user', Yii::$app->user->identity);
        }

        /** @var ProfileEvent $event */
        $event = $this->make(ProfileEvent::class, [$profile]);

        $this->make(AjaxRequestModelValidator::class, [$profile])->validate();

        $uploadProfileIconImageForm = new UploadProfileIconImageForm();

        if ($profile->load(Yii::$app->request->post())) {
            $this->trigger(UserEvent::EVENT_BEFORE_PROFILE_UPDATE, $event);
            if ($profile->save()) {

                $uploadProfileIconImageForm->iconImageFile = UploadedFile::getInstance($uploadProfileIconImageForm,
                    'iconImageFile');
                if ($uploadProfileIconImageForm->upload($profile)) {
                    //return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    //TODO: обработка ошибки
                }

                Yii::$app->getSession()->setFlash('success', Yii::t('usuario', 'Your profile has been updated'));
                $this->trigger(UserEvent::EVENT_AFTER_PROFILE_UPDATE, $event);

                return $this->refresh();
            }
        }

        return $this->render(
            'profile',
            [
                'model' => $profile,
                'uploadProfileIconImageForm' => $uploadProfileIconImageForm,
            ]
        );
    }

    public function actionImageDelete()
    {
        $profileModel = $this->profileQuery->whereUserId(Yii::$app->user->identity->getId())->one();

        $uploadProfileIconImageForm = new UploadProfileIconImageForm();
        $uploadProfileIconImageForm->removeIconImage($profileModel);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return json_encode(true);
    }
}
