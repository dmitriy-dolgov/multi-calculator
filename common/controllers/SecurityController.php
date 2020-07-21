<?php

namespace common\controllers;

use Da\User\Model\SocialNetworkAccount;
use Da\User\Model\User;
use Yii;
use Da\User\Contracts\AuthClientInterface;
use Da\User\Controller\SecurityController as BaseController;
use Da\User\Service\SocialNetworkAuthenticateService;
use yii\helpers\Url;

class SecurityController extends BaseController
{
    protected function snCreateAccount($client, SocialNetworkAuthenticateService $socialNetworkAuthenticateService)
    {
        $data = $client->getUserAttributes();

        // @var SocialNetworkAccount $account
        $account = $this->make(
            SocialNetworkAccount::class,
            [],
            [
                'provider' => $client->getId(),
                'client_id' => $data['id'],
                'data' => json_encode($data),
                'username' => $client->getUserName(),
                'email' => $client->getEmail(),
            ]
        );

        $user = User::findOne(['email' => $account->email]);
        if ($user instanceof User) {
            $account->user_id = $user->id;
            $account->save(false);
        }

        return $account;
    }

    public function authenticate(AuthClientInterface $client)
    {
        $socialNetworkAuthenticateService = $this->make(SocialNetworkAuthenticateService::class,
            [$this, $this->action, $client]);

        $account = $this->socialNetworkAccountQuery->whereClient($client)->one();
        if ($account === null) {

            $account = $this->snCreateAccount($client, $socialNetworkAuthenticateService);
            if (!$account) {
                Yii::$app->session->setFlash('danger', Yii::t('usuario', 'Unable to create an account.'));
                $socialNetworkAuthenticateService->action->setSuccessUrl(Url::to(['/user/security/login']));

                return false;
            }

            $account->created_at = time();
        }

        if (!$account->user_id) {
            $data = $client->getUserAttributes();
            //TODO: разобраться если email нет
            $email = $client->getEmail();
            $user = new \common\models\db\User();
            //TODO: Это в профиль !!!
            //$user->username = trim($data['first_name'] . ' ' . $data['last_name']);
            //TODO: проверять в других системах кроме VK
            $user->username = $data['screen_name'];
            $user->password_hash = 'social_account_yet';
            $user->email = $email;
            //TODO: сделать
            //$user->registration_ip = '';
            $user->confirmed_at = time();
            $user->created_at = time();
            $user->last_login_at = time();
            //TODO: сделать
            //$user->last_login_ip
            //TODO: сделать
            //$user->login_lat_long
            $user->save();
            $account->user_id = $user->getPrimaryKey();
        }

        $account->save();

        $socialNetworkAuthenticateService->run();
    }
}
