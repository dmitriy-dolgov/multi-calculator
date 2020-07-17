<?php

namespace app\components;

use Yii;
use app\models\db\User;
use yii\base\Component;
use yii\web\BadRequestHttpException;

class MapHandler extends Component
{
    public function getDefaultStartPointParams(): array
    {
        return Yii::$app->params['map_default_places_by_language'][\Yii::$app->language] ?? Yii::$app->params['map_default_places_by_language']['not_set'];
    }

    public function getStartPointParamsForUser(User $user = null): array
    {
        $user = $this->setUpDefaultUser($user);

        if (!empty($user->profile)) {
            if ($user->profile->company_lat_long) {
                $result = $this->latLongString2Array($user->profile->company_lat_long);
                $result['zoom'] = 15;
                return $result;
            }
        }

        if ($user->login_lat_long) {
            $result = $this->latLongString2Array($user->login_lat_long);
            $result['zoom'] = 7;
            return $result;
        }


        return self::getDefaultStartPointParams();
    }

    /**
     * Вернуть координаты всех местоположений пользователя (в перспективе).
     * На данный момент есть только одно местоположение.
     *
     * @param User|null $user
     * @return array
     */
    public function getUserMarkers(User $user = null): array
    {
        $user = $this->setUpDefaultUser($user);

        if (!isset($user->profile)) {
            return [];
        }

        $result = [];

        if ($user->profile->company_lat_long) {
            $result[] = $this->latLongString2Array($user->profile->company_lat_long);
        }

        return $result;
    }

    protected function setUpDefaultUser(?User $user)
    {
        return $user ?? Yii::$app->user->identity;
    }

    protected function latLongString2Array(string $latLong)
    {
        [$result['latitude'], $result['longitude']] = explode(';', $latLong);

        return $result;
    }

    protected function latLongArray2String(array $latLong)
    {
        return $latLong['latitude'] . ';' . $latLong['longitude'];
    }

    public function ifUserHasPlaceMarkOnMap(User $user = null): bool
    {
        return (bool)$this->setUpDefaultUser($user)->profile->company_lat_long;
    }

    //public function setupLoginLatLong(\Da\User\Model\User $user)
    public function setupLoginLatLong(User $user)
    {
        //TODO:проверить не сменился ли IP
        if (!$user->login_lat_long) {
            if ($ip = $user->last_login_ip ?: $user->registration_ip) {

                if (filter_var($ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== $ip) {
                    if (YII_DEBUG) {
                        $ip = '178.219.186.12';     // test string, Moscow IP
                    } else {
                        return;
                    }
                }

                $url = 'http://api.ipstack.com/' . $ip . '?access_key=' . urlencode(Yii::$app->params['ipstack_access_key']);
                try {
                    $ctx = stream_context_create([
                        'http' => [
                            'timeout' => 17,
                        ],
                    ]);
                    $response = file_get_contents($url, false, $ctx);
                    $idData = (new \yii\web\JsonParser)->parse($response, 'application/json');
                    if (isset($idData['latitude']) && isset($idData['longitude'])) {
                        $user->updateAttributes([
                            'login_lat_long' => $this->latLongArray2String($idData),
                        ]);
                    } else {
                        throw new BadRequestHttpException('Wrong JSON data in response body: '
                            . print_r($idData, true));
                    }
                } catch (BadRequestHttpException $e) {
                    Yii::error($e->getMessage());
                }
            } else {
                Yii::error('User has no IP. User ID: ' . $user->getId());
            }
        }
    }
}
