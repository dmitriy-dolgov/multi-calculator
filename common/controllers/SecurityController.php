<?php

namespace app\controllers;

use Da\User\Model\User;
use Yii;
use Da\User\Contracts\AuthClientInterface;
use Da\User\Controller\SecurityController as BaseController;
use Da\User\Service\SocialNetworkAuthenticateService;
use yii\helpers\Url;

class SecurityController extends BaseController
{
    public function authenticate(AuthClientInterface $client)
    {
        $socialNetworkAuthenticateService = $this->make(SocialNetworkAuthenticateService::class, [$this, $this->action, $client]);

        //$socialNetworkAuthenticateService->run();

        $account = $this->socialNetworkAccountQuery->whereClient($this->client)->one();
        if ($account === null) {
            $account = $socialNetworkAuthenticateService->createAccount();
            if (!$account) {
                Yii::$app->session->setFlash('danger', Yii::t('usuario', 'Unable to create an account.'));
                $socialNetworkAuthenticateService->authAction->setSuccessUrl(Url::to(['/user/security/login']));

                return false;
            }

            $account->created_at = time();

            if (!($account->user instanceof User)) {
                $data = $socialNetworkAuthenticateService->client->getUserAttributes();
                $account->user = new \common\models\db\User();
                $account->user->username = $data['first_name'];
            }

            $account->save();
        }
    }
}
